<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

   

class EstudianteController extends Controller
{
      /**
     * @OA\Get(
     *     path="/api/estudiantes",
     *     summary="Listar estudiantes",
     *     tags={"Estudiantes"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de Estudiantes"
     *     )
     * )
     */
    public function index()
    {
        Log::info('Listado de estudiantes solicitado.');
        $estudiantes = Estudiante::all();
        Log::info('Estudiantes obtenidos:', ['count' => $estudiantes->count()]);
        return response()->json($estudiantes);
    }
 /**
 * @OA\Post(
 *     path="/api/estudiantes",
 *     summary="Crear un nuevo estudiante",
 *     tags={"Estudiantes"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nombre","cedula","correo","paralelo_id"},
 *             @OA\Property(property="nombre", type="string", example="Juan Perez"),
 *             @OA\Property(property="cedula", type="string", example="1234567890"),
 *             @OA\Property(property="correo", type="string", format="email", example="juan@example.com"),
 *             @OA\Property(property="paralelo_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="Accept",
 *         in="header",
 *         required=true,
 *         @OA\Schema(type="string", default="application/json"),
 *         description="Indica que se espera una respuesta en formato JSON"
 *     ),
 *     @OA\Response(response=201, description="Estudiante creado exitosamente"),
 *     @OA\Response(response=422, description="Errores de validacion")
 * )
 */
      
    public function store(Request $request)
    {
        Log::info('Creando estudiante con datos:', $request->all());

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|string|max:20|unique:estudiantes',
            'correo' => 'required|email|unique:estudiantes',
            'paralelo_id' => 'required|exists:paralelos,id',
        ]);

        $estudiante = Estudiante::create($validated);

        Log::info('Estudiante creado correctamente', ['id' => $estudiante->id]);

        return response()->json([
            'mensaje' => 'Estudiante creado correctamente',
            'estudiante' => $estudiante,
        ], 201);
    }

    public function show($id)
    {
        Log::info("Mostrando estudiante con ID: $id");
        $estudiante = Estudiante::findOrFail($id);
        return response()->json($estudiante);
    }
    /**
 * @OA\Put(
 *     path="/api/estudiantes/{id}",
 *     summary="Actualizar un estudiante existente",
 *     tags={"Estudiantes"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID del estudiante a actualizar",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="nombre", type="string", example="Pedro Gómez"),
 *             @OA\Property(property="cedula", type="string", example="1101234568"),
 *             @OA\Property(property="correo", type="string", format="email", example="pedro@example.com"),
 *             @OA\Property(property="paralelo_id", type="integer", example=2)
 *         )
 *     ),
 *     @OA\Response(response=200, description="Estudiante actualizado correctamente"),
 *     @OA\Response(response=404, description="Estudiante no encontrado")
 * )
 */


    public function update(Request $request, $id)
    {
        Log::info("Actualizando estudiante con ID: $id", $request->all());

        $estudiante = Estudiante::find($id);

        if (!$estudiante) {
            Log::warning("Estudiante no encontrado con ID: $id");
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|string|max:20|unique:estudiantes,cedula,' . $id,
            'correo' => 'required|email|unique:estudiantes,correo,' . $id,
            'paralelo_id' => 'required|exists:paralelos,id',
        ]);

        $estudiante->update($validated);

        Log::info("Estudiante actualizado correctamente", ['id' => $estudiante->id]);

        return response()->json([
            'message' => 'Estudiante actualizado exitosamente',
            'data' => $estudiante
        ]);
    }
/**
 * @OA\Delete(
 *     path="/api/estudiantes/{id}",
 *     summary="Eliminar un estudiante",
 *     tags={"Estudiantes"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID del estudiante a eliminar",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Estudiante eliminado correctamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Estudiante eliminado")
 *         )
 *     ),
 *     @OA\Response(response=404, description="Estudiante no encontrado")
 * )
 */


    public function destroy($id)
    {
        Log::info("Eliminando estudiante con ID: $id");

        $estudiante = Estudiante::findOrFail($id);
        $estudiante->delete();

        Log::info("Estudiante eliminado correctamente", ['id' => $id]);

        return response()->json([
            'message' => 'Estudiante eliminado exitosamente',
            'data' => $estudiante
            ]);
    }
}
