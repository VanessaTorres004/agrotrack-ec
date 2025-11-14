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
    public function productores()
    {
        $productores = User::where('role', 'productor')
            ->withCount(['fincas'])
            ->with(['fincas.cultivos'])
            ->get()
            ->map(function($productor) {
                $cultivos = $productor->fincas->flatMap->cultivos;
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
        $totalHectareas = Cultivo::sum('hectareas');
        
        // Producción y ventas
        $totalProduccion = Cosecha::whereBetween('fecha_cosecha', [$fechaInicio, $fechaFin])
            ->sum('cantidad_kg');
        
        $totalVentas = Venta::whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
            ->sum('total');
        
        // Rendimiento promedio por cultivo
        $rendimientoPorCultivo = Cultivo::select('nombre', DB::raw('AVG(hectareas) as hectareas_promedio'))
            ->groupBy('nombre')
            ->get();
        
        // Top 5 productores por producción
        $topProductores = User::where('role', 'productor')
            ->withSum(['fincas.cultivos.cosechas' => function($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_cosecha', [$fechaInicio, $fechaFin]);
            }], 'cantidad_kg')
            ->orderByDesc('fincas_cultivos_cosechas_sum_cantidad_kg')
            ->limit(5)
            ->get()
            ->map(function($productor) {
                return [
                    'nombre' => $productor->name,
                    'produccion' => $productor->fincas_cultivos_cosechas_sum_cantidad_kg ?? 0,
                ];
            });
        
        // Distribución de cultivos
        $distribucionCultivos = Cultivo::select('nombre', DB::raw('COUNT(*) as total'))
            ->groupBy('nombre')
            ->get();
        
        // IDC promedio
        $promedioIDC = Indicador::selectRaw('AVG(idc) as promedio')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('indicadores')
                    ->groupBy('cultivo_id');
            })
            ->value('promedio') ?? 0;

        return view('admin.reportes', compact(
            'fechaInicio',
            'fechaFin',
            'totalProductores',
            'totalCultivos',
            'totalHectareas',
            'totalProduccion',
            'totalVentas',
            'rendimientoPorCultivo',
            'topProductores',
            'distribucionCultivos',
            'promedioIDC'
        ));
    }

    public function exportarPDF(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->subMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->format('Y-m-d'));

        // Reutilizar la lógica de reportes
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
            'total_ventas' => Venta::whereBetween('fecha_venta', [$fechaInicio, $fechaFin])->sum('total'),
        ];
    }
}
