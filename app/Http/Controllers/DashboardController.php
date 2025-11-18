<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cultivo;
use App\Models\Alerta;
use App\Models\Indicador;
use App\Models\Ganado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalProductores = User::where('role', 'productor')->count();
        $cultivosActivos = Cultivo::where('estado', 'activo')->count();
        $totalGanado = Ganado::count();
        
        $promedioIDC = Indicador::selectRaw('AVG(idc) as promedio')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('indicadores')
                    ->groupBy('cultivo_id');
            })
            ->value('promedio') ?? 0;
        
        $alertasActivas = Alerta::where('leida', false)->count();
        
        // Rendimiento por cultivo
        $rendimientoPorCultivo = Cultivo::select('nombre', DB::raw('AVG(indicadores.rendimiento) as promedio'))
            ->join('indicadores', 'cultivos.id', '=', 'indicadores.cultivo_id')
            ->groupBy('cultivos.nombre')
            ->get();
        
        // Clasificación IDC
        $clasificacionIDC = Indicador::select('clasificacion', DB::raw('COUNT(*) as total'))
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('indicadores')
                    ->groupBy('cultivo_id');
            })
            ->groupBy('clasificacion')
            ->get();
        
        // Productores con información
        $productores = User::where('role', 'productor')
            ->with(['fincas.cultivos.indicadores' => function($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->map(function($productor) {
                $cultivos = $productor->fincas->flatMap(function($finca) {
                    return $finca->cultivos;
                });
                return [
                    'nombre' => $productor->name,
                    'finca' => $productor->fincas->first()?->nombre ?? 'N/A',
                    'cultivos' => $cultivos->count(),
                    'promedio_idc' => $cultivos->avg('idc_actual'),
                    'estado' => $cultivos->avg('idc_actual') >= 80 ? 'Bueno' : 'Requiere atención',
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

    public function productor()
    {
        $user = auth()->user();
        $fincas = $user->fincas()->with(['cultivos.indicadores' => function($query) {
            $query->latest()->limit(1);
        }])->get();
        
        $alertas = Alerta::whereHas('cultivo.finca', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('leida', false)->latest()->get();
        
        return view('productor.dashboard', compact('fincas', 'alertas'));
    }
}
