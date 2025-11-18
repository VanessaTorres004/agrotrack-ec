<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cultivo;
use App\Models\Finca;
use App\Models\Cosecha;
use App\Models\Venta;
use App\Models\Indicador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Estadísticas generales
        $totalProductores = User::where('role', 'productor')->count();
        $cultivosActivos = Cultivo::where('estado', 'activo')->count();
        $totalGanado = DB::table('ganado')->count();
        
        // Calcular promedio IDC
        $promedioIDC = Indicador::whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('indicadores')
                    ->groupBy('cultivo_id');
            })
            ->avg('idc') ?? 0;
        
        // Alertas activas (cultivos con IDC crítico < 40)
        $alertasActivas = Indicador::whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('indicadores')
                    ->groupBy('cultivo_id');
            })
            ->where('idc', '<', 40)
            ->count();
        
        // Rendimiento por cultivo (promedio IDC por tipo de cultivo)
        $rendimientoPorCultivo = Cultivo::select('nombre')
            ->selectRaw('AVG(
                (SELECT idc FROM indicadores 
                 WHERE indicadores.cultivo_id = cultivos.id 
                 ORDER BY created_at DESC 
                 LIMIT 1)
            ) as promedio')
            ->groupBy('nombre')
            ->get();
        
        // Clasificación IDC - FIXED
        $ultimosIndicadores = Indicador::whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('indicadores')
                    ->groupBy('cultivo_id');
            })
            ->get();
        
        $clasificacionIDC = $ultimosIndicadores->groupBy(function($indicador) {
            if ($indicador->idc >= 80) return 'excelente';
            if ($indicador->idc >= 60) return 'bueno';
            if ($indicador->idc >= 40) return 'en_riesgo';
            return 'critico';
        })->map(function($grupo, $clasificacion) {
            return (object)[
                'clasificacion' => $clasificacion,
                'total' => $grupo->count()
            ];
        })->values();
        
        // Resumen de productores
        $productores = User::where('role', 'productor')
            ->with(['fincas.cultivos.indicadores' => function($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->map(function($productor) {
                $fincas = $productor->fincas;
                $totalCultivos = $fincas->sum(function($finca) {
                    return $finca->cultivos->count();
                });
                
                $todosIndicadores = $fincas->flatMap(function($finca) {
                    return $finca->cultivos->flatMap(function($cultivo) {
                        return $cultivo->indicadores;
                    });
                });
                
                $promedioIDC = $todosIndicadores->avg('idc') ?? 0;
                
                return [
                    'nombre' => $productor->name,
                    'finca' => $fincas->first()?->nombre ?? 'Sin finca',
                    'cultivos' => $totalCultivos,
                    'promedio_idc' => $promedioIDC,
                    'estado' => $promedioIDC >= 60 ? 'Bueno' : 'Requiere atención',
                ];
            });
        
        return view('admin.dashboard', compact(
            'totalProductores',
            'cultivosActivos',
            'totalGanado',
            'promedioIDC',
            'alertasActivas',
            'rendimientoPorCultivo',
            'clasificacionIDC',
            'productores'
        ));
    }

    public function productores()
    {
        $productores = User::where('role', 'productor')
            ->withCount(['fincas'])
            ->with(['fincas.cultivos'])
            ->get()
            ->map(function($productor) {
                $cultivos = $productor->fincas->flatMap(function($finca) {
                    return $finca->cultivos;
                });
                $promedioIDC = $cultivos->avg('idc_actual') ?? 0;
                
                return [
                    'id' => $productor->id,
                    'nombre' => $productor->name,
                    'email' => $productor->email,
                    'fincas' => $productor->fincas_count,
                    'cultivos' => $cultivos->count(),
                    'promedio_idc' => round($promedioIDC, 2),
                    'estado' => $promedioIDC >= 80 ? 'Excelente' : ($promedioIDC >= 60 ? 'Bueno' : 'Requiere atención'),
                ];
            });

        return view('admin.productores', compact('productores'));
    }

    public function cultivos()
    {
        $cultivos = Cultivo::with(['finca.user', 'indicadores' => function($query) {
            $query->latest()->limit(1);
        }])
        ->get()
        ->map(function($cultivo) {
            $ultimoIndicador = $cultivo->indicadores->first();
            
            return [
                'id' => $cultivo->id,
                'nombre' => $cultivo->nombre,
                'variedad' => $cultivo->variedad,
                'finca' => $cultivo->finca->nombre,
                'productor' => $cultivo->finca->user->name,
                'hectareas' => $cultivo->hectareas,
                'estado' => $cultivo->estado,
                'idc' => $ultimoIndicador?->idc ?? 'N/A',
                'clasificacion' => $ultimoIndicador?->clasificacion ?? 'Sin datos',
                'fecha_siembra' => $cultivo->fecha_siembra->format('d/m/Y'),
            ];
        });

        return view('admin.cultivos', compact('cultivos'));
    }

    public function reportes(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->subMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->format('Y-m-d'));

        // Estadísticas generales
        $totalProductores = User::where('role', 'productor')->count();
        $totalCultivos = Cultivo::count();
        $cultivosActivos = Cultivo::where('estado', 'activo')->count();
        $totalHectareas = Cultivo::sum('hectareas');
        $totalGanado = DB::table('ganado')->count();
        
        // Producción y ventas
        $totalProduccion = Cosecha::whereBetween('fecha_cosecha', [$fechaInicio, $fechaFin])
            ->sum('cantidad_kg');
        
        $totalVentas = Venta::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('total');
        
        // Rendimiento promedio por cultivo
        $rendimientoPorCultivo = Cultivo::select('nombre')
            ->selectRaw('AVG(
                (SELECT idc FROM indicadores 
                 WHERE indicadores.cultivo_id = cultivos.id 
                 ORDER BY created_at DESC 
                 LIMIT 1)
            ) as promedio')
            ->groupBy('nombre')
            ->get();
        
        // Top 5 productores por producción
        $topProductores = User::where('role', 'productor')
            ->with(['fincas.cultivos.cosechas' => function($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_cosecha', [$fechaInicio, $fechaFin]);
            }])
            ->get()
            ->map(function($productor) {
                $totalProduccion = $productor->fincas->flatMap(function($finca) {
                    return $finca->cultivos;
                })->flatMap(function($cultivo) {
                    return $cultivo->cosechas;
                })->sum('cantidad_kg');
                
                return [
                    'nombre' => $productor->name,
                    'produccion' => $totalProduccion,
                ];
            })
            ->sortByDesc('produccion')
            ->take(5)
            ->values();
        
        // Distribución de cultivos
        $distribucionCultivos = Cultivo::select('nombre', DB::raw('COUNT(*) as total'))
            ->groupBy('nombre')
            ->get();
        
        // IDC promedio
        $promedioIDC = Indicador::whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('indicadores')
                    ->groupBy('cultivo_id');
            })
            ->avg('idc') ?? 0;

        // Alertas activas
        $alertasActivas = Indicador::whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('indicadores')
                    ->groupBy('cultivo_id');
            })
            ->where('idc', '<', 40)
            ->count();

        // Clasificación IDC - FIXED
        $ultimosIndicadores = Indicador::whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('indicadores')
                    ->groupBy('cultivo_id');
            })
            ->get();
        
        $clasificacionIDC = $ultimosIndicadores->groupBy(function($indicador) {
            if ($indicador->idc >= 80) return 'excelente';
            if ($indicador->idc >= 60) return 'bueno';
            if ($indicador->idc >= 40) return 'en_riesgo';
            return 'critico';
        })->map(function($grupo, $clasificacion) {
            return (object)[
                'clasificacion' => $clasificacion,
                'total' => $grupo->count()
            ];
        })->values();

        // Resumen de productores
        $productores = User::where('role', 'productor')
            ->with(['fincas.cultivos.indicadores' => function($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->map(function($productor) {
                $fincas = $productor->fincas;
                $totalCultivos = $fincas->sum(function($finca) {
                    return $finca->cultivos->count();
                });
                
                $todosIndicadores = $fincas->flatMap(function($finca) {
                    return $finca->cultivos->flatMap(function($cultivo) {
                        return $cultivo->indicadores;
                    });
                });
                
                $promedioIDC = $todosIndicadores->avg('idc') ?? 0;
                
                return [
                    'nombre' => $productor->name,
                    'finca' => $fincas->first()?->nombre ?? 'Sin finca',
                    'cultivos' => $totalCultivos,
                    'promedio_idc' => $promedioIDC,
                    'estado' => $promedioIDC >= 60 ? 'Bueno' : 'Requiere atención',
                ];
            });

        return view('admin.reportes', compact(
            'fechaInicio',
            'fechaFin',
            'totalProductores',
            'totalCultivos',
            'cultivosActivos',
            'totalGanado',
            'totalHectareas',
            'totalProduccion',
            'totalVentas',
            'rendimientoPorCultivo',
            'topProductores',
            'distribucionCultivos',
            'promedioIDC',
            'alertasActivas',
            'clasificacionIDC',
            'productores'
        ));
    }

    public function exportarPDF(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->subMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->format('Y-m-d'));

        $data = $this->obtenerDatosReporte($fechaInicio, $fechaFin);

        $pdf = Pdf::loadView('admin.reportes-pdf', $data);
        return $pdf->download('reporte-agricola-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportarCSV(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->subMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->format('Y-m-d'));

        $cultivos = Cultivo::with(['finca.user', 'cosechas' => function($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('fecha_cosecha', [$fechaInicio, $fechaFin]);
        }])->get();

        $filename = 'reporte-cultivos-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($cultivos) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Productor', 'Finca', 'Cultivo', 'Variedad', 'Hectáreas', 'Estado', 'Producción Total (kg)']);

            foreach ($cultivos as $cultivo) {
                fputcsv($file, [
                    $cultivo->finca->user->name,
                    $cultivo->finca->nombre,
                    $cultivo->nombre,
                    $cultivo->variedad,
                    $cultivo->hectareas,
                    $cultivo->estado,
                    $cultivo->cosechas->sum('cantidad_kg'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function obtenerDatosReporte($fechaInicio, $fechaFin)
    {
        return [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'total_productores' => User::where('role', 'productor')->count(),
            'total_cultivos' => Cultivo::count(),
            'total_produccion' => Cosecha::whereBetween('fecha_cosecha', [$fechaInicio, $fechaFin])->sum('cantidad_kg'),
            'total_ventas' => Venta::whereBetween('fecha', [$fechaInicio, $fechaFin])->sum('total'),
        ];
    }
}