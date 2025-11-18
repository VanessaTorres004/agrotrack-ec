<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Finca;
use App\Models\Cultivo;
use App\Models\Ganado;
use App\Models\Maquinaria;
use Illuminate\Http\Request;

class DropdownController extends Controller
{
    /**
     * Get fincas for authenticated user
     */
    public function getFincas()
    {
        $fincas = Finca::where('user_id', auth()->id())
            ->select('id', 'nombre', 'provincia', 'canton')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $fincas
        ]);
    }

    /**
     * Get cultivos by finca
     */
    public function getCultivosByFinca(Request $request)
    {
        $request->validate([
            'finca_id' => 'required|integer|exists:fincas,id'
        ]);

        // Verify ownership
        $finca = Finca::where('id', $request->finca_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$finca) {
            return response()->json([
                'success' => false,
                'message' => 'Finca no encontrada'
            ], 404);
        }

        $cultivos = Cultivo::where('finca_id', $request->finca_id)
            ->where('estado', 'activo')
            ->select('id', 'nombre', 'variedad', 'area')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cultivos
        ]);
    }

    /**
     * Get ganado by finca
     */
    public function getGanadoByFinca(Request $request)
    {
        $request->validate([
            'finca_id' => 'required|integer|exists:fincas,id'
        ]);

        $finca = Finca::where('id', $request->finca_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$finca) {
            return response()->json([
                'success' => false,
                'message' => 'Finca no encontrada'
            ], 404);
        }

        $ganado = Ganado::where('finca_id', $request->finca_id)
            ->select('id', 'identificacion', 'tipo', 'raza', 'estado_salud')
            ->orderBy('identificacion')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $ganado
        ]);
    }

    /**
     * Get ganado by tipo
     */
    public function getGanadoByTipo(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string|in:vacuno,porcino,ovino,caprino,aviar,equino'
        ]);

        $ganado = Ganado::whereHas('finca', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('tipo', $request->tipo)
            ->select('id', 'identificacion', 'raza', 'finca_id')
            ->with('finca:id,nombre')
            ->orderBy('identificacion')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $ganado
        ]);
    }

    /**
     * Get maquinaria by finca
     */
    public function getMaquinariaByFinca(Request $request)
    {
        $request->validate([
            'finca_id' => 'required|integer|exists:fincas,id'
        ]);

        $finca = Finca::where('id', $request->finca_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$finca) {
            return response()->json([
                'success' => false,
                'message' => 'Finca no encontrada'
            ], 404);
        }

        $maquinaria = Maquinaria::where('finca_id', $request->finca_id)
            ->select('id', 'nombre', 'tipo', 'estado')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $maquinaria
        ]);
    }

    /**
     * Get provincias de Ecuador
     */
    public function getProvincias()
    {
        $provincias = [
            ['id' => 'azuay', 'nombre' => 'Azuay'],
            ['id' => 'bolivar', 'nombre' => 'Bolívar'],
            ['id' => 'canar', 'nombre' => 'Cañar'],
            ['id' => 'carchi', 'nombre' => 'Carchi'],
            ['id' => 'chimborazo', 'nombre' => 'Chimborazo'],
            ['id' => 'cotopaxi', 'nombre' => 'Cotopaxi'],
            ['id' => 'el_oro', 'nombre' => 'El Oro'],
            ['id' => 'esmeraldas', 'nombre' => 'Esmeraldas'],
            ['id' => 'guayas', 'nombre' => 'Guayas'],
            ['id' => 'imbabura', 'nombre' => 'Imbabura'],
            ['id' => 'loja', 'nombre' => 'Loja'],
            ['id' => 'los_rios', 'nombre' => 'Los Ríos'],
            ['id' => 'manabi', 'nombre' => 'Manabí'],
            ['id' => 'morona_santiago', 'nombre' => 'Morona Santiago'],
            ['id' => 'napo', 'nombre' => 'Napo'],
            ['id' => 'orellana', 'nombre' => 'Orellana'],
            ['id' => 'pastaza', 'nombre' => 'Pastaza'],
            ['id' => 'pichincha', 'nombre' => 'Pichincha'],
            ['id' => 'santa_elena', 'nombre' => 'Santa Elena'],
            ['id' => 'santo_domingo', 'nombre' => 'Santo Domingo de los Tsáchilas'],
            ['id' => 'sucumbios', 'nombre' => 'Sucumbíos'],
            ['id' => 'tungurahua', 'nombre' => 'Tungurahua'],
            ['id' => 'zamora_chinchipe', 'nombre' => 'Zamora Chinchipe'],
        ];

        return response()->json([
            'success' => true,
            'data' => $provincias
        ]);
    }

    /**
     * Get cantones by provincia
     */
    public function getCantonesByProvincia(Request $request)
    {
        $request->validate([
            'provincia' => 'required|string'
        ]);

        // Sample data - In production, use a database or API
        $cantones = $this->getCantonesData($request->provincia);

        return response()->json([
            'success' => true,
            'data' => $cantones
        ]);
    }

    private function getCantonesData($provincia)
    {
        $cantonesMap = [
            'pichincha' => [
                ['id' => 'quito', 'nombre' => 'Quito'],
                ['id' => 'cayambe', 'nombre' => 'Cayambe'],
                ['id' => 'mejia', 'nombre' => 'Mejía'],
                ['id' => 'pedro_moncayo', 'nombre' => 'Pedro Moncayo'],
                ['id' => 'rumiñahui', 'nombre' => 'Rumiñahui'],
            ],
            'guayas' => [
                ['id' => 'guayaquil', 'nombre' => 'Guayaquil'],
                ['id' => 'duran', 'nombre' => 'Durán'],
                ['id' => 'milagro', 'nombre' => 'Milagro'],
                ['id' => 'daule', 'nombre' => 'Daule'],
                ['id' => 'samborondon', 'nombre' => 'Samborondón'],
            ],
            // Add more provinces as needed
        ];

        return $cantonesMap[$provincia] ?? [];
    }
}
