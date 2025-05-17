<?php

namespace App\Http\Controllers;

use App\Models\Paralelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
/**
 * @OA\Schema(
 *     schema="Paralelo",
 *     type="object",
 *     title="Paralelo",
 *     required={"id", "nombre"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Paralelo A"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class ParaleloController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/paralelos",
     *     summary="Listar todos los paralelos",
     *     tags={"Paralelos"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de paralelos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Paralelo"))
     *     )
     * )
     */
    public function index()
    {
        Log::info('Listado de paralelos solicitado.');
        $paralelos = Paralelo::all();
        Log::info('Paralelos obtenidos', ['count' => $paralelos->count()]);
        return response()->json($paralelos);
    }

    /**
     * @OA\Post(
     *     path="/api/paralelos",
     *     summary="Crear un nuevo paralelo",
     *     tags={"Paralelos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="Paralelo A")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Paralelo creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Paralelo")
     *     ),
     *     @OA\Response(response=422, description="Errores de validación")
     * )
     */
    public function store(Request $request)
    {
        Log::info('Creando paralelo con datos:', $request->all());

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $paralelo = Paralelo::create($validated);

        Log::info('Paralelo creado correctamente', ['id' => $paralelo->id]);

        return response()->json([
            'message' => 'Paralelo creado exitosamente',
            'data' => $paralelo
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/paralelos/{id}",
     *     summary="Mostrar un paralelo por ID",
     *     tags={"Paralelos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del paralelo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Paralelo encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Paralelo")
     *     ),
     *     @OA\Response(response=404, description="Paralelo no encontrado")
     * )
     */
    public function show($id)
    {
        Log::info("Mostrando paralelo con ID: $id");
        $paralelo = Paralelo::with('estudiantes')->find($id);

        if (!$paralelo) {
            Log::warning("Paralelo no encontrado con ID: $id");
            return response()->json(['message' => 'Paralelo no encontrado'], 404);
        }

        return response()->json($paralelo);
    }

    /**
     * @OA\Put(
     *     path="/api/paralelos/{id}",
     *     summary="Actualizar un paralelo",
     *     tags={"Paralelos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del paralelo a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="Paralelo B")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Paralelo actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Paralelo")
     *     ),
     *     @OA\Response(response=404, description="Paralelo no encontrado"),
     *     @OA\Response(response=422, description="Errores de validación")
     * )
     */
    public function update(Request $request, $id)
    {
        Log::info("Actualizando paralelo con ID: $id", $request->all());

        $paralelo = Paralelo::find($id);

        if (!$paralelo) {
            Log::warning("Paralelo no encontrado con ID: $id");
            return response()->json(['message' => 'Paralelo no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $paralelo->update($validated);

        Log::info("Paralelo actualizado correctamente", ['id' => $paralelo->id]);

        return response()->json([
            'message' => 'Paralelo actualizado exitosamente',
            'data' => $paralelo
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/paralelos/{id}",
     *     summary="Eliminar un paralelo",
     *     tags={"Paralelos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del paralelo a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Paralelo eliminado exitosamente"),
     *     @OA\Response(response=404, description="Paralelo no encontrado")
     * )
     */
    public function destroy($id)
    {
        Log::info("Eliminando paralelo con ID: $id");

        $paralelo = Paralelo::find($id);

        if (!$paralelo) {
            Log::warning("Paralelo no encontrado con ID: $id");
            return response()->json(['message' => 'Paralelo no encontrado'], 404);
        }

        $paralelo->delete();

        Log::info("Paralelo eliminado correctamente", ['id' => $id]);

        return response()->json(['message' => 'Paralelo eliminado exitosamente']);
    }
}
