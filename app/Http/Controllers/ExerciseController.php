<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise;
use App\Services\ExerciseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Exercises",
 *     description="Gerenciamento de exercícios"
 * )
 */
class ExerciseController extends Controller
{
    public function __construct(private ExerciseService $service) {}

    /**
     * @OA\Get(
     *     path="/api/exercises",
     *     tags={"Exercises"},
     *     summary="Lista todos os exercícios cadastrados",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de exercícios"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $exercises = Exercise::orderBy('name')->get();
        return response()->json(ExerciseResource::collection($exercises));
    }

    /**
     * @OA\Post(
     *     path="/api/exercises",
     *     tags={"Exercises"},
     *     summary="Cria um novo exercício",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Supino reto"),
     *             @OA\Property(property="category", type="string", example="Peito")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Exercício criado com sucesso")
     * )
     */
    public function store(StoreExerciseRequest $request): JsonResponse
    {
        $exercise = $this->service->store($request->validated());
        return response()->json(new ExerciseResource($exercise));
    }

    /**
     * @OA\Get(
     *     path="/api/exercises/{id}",
     *     tags={"Exercises"},
     *     summary="Visualiza um exercício específico",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do exercício",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Exercício retornado com sucesso"),
     *     @OA\Response(response=404, description="Exercício não encontrado")
     * )
     */
    public function show(Exercise $exercise): JsonResponse
    {
        return response()->json(new ExerciseResource($exercise));
    }

    /**
     * @OA\Put(
     *     path="/api/exercises/{id}",
     *     tags={"Exercises"},
     *     summary="Atualiza um exercício existente",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do exercício",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Supino inclinado"),
     *             @OA\Property(property="category", type="string", example="Peito")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Exercício atualizado com sucesso"),
     *     @OA\Response(response=404, description="Exercício não encontrado")
     * )
     */
    public function update(UpdateExerciseRequest $request, Exercise $exercise): JsonResponse
    {
        $updated = $this->service->update($exercise, $request->validated());
        return response()->json(new ExerciseResource($updated));
    }

    /**
     * @OA\Delete(
     *     path="/api/exercises/{id}",
     *     tags={"Exercises"},
     *     summary="Remove um exercício",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do exercício",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Exercício removido com sucesso"),
     *     @OA\Response(response=404, description="Exercício não encontrado")
     * )
     */
    public function destroy(Exercise $exercise): JsonResponse
    {
        $this->service->destroy($exercise);
        return response()->json(['message' => 'Exercício removido com sucesso.']);
    }

    /**
     * @OA\Get(
     *     path="/api/exercises/popular",
     *     tags={"Exercises"},
     *     summary="Lista os exercícios mais utilizados pelo usuário",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de exercícios mais utilizados",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="exercise_id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Supino reto"),
     *                 @OA\Property(property="category", type="string", example="Peito"),
     *                 @OA\Property(property="times_used", type="integer", example=15)
     *             )
     *         )
     *     )
     * )
     */
    public function popular(Request $request): JsonResponse
    {
        $user = $request->user();

        $workoutIds = $user->workouts()->pluck('id');

        $exerciseCounts = DB::table('workout_sets')
            ->select('exercise_id', DB::raw('count(*) as total'))
            ->whereIn('workout_id', $workoutIds)
            ->groupBy('exercise_id')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $exerciseIds = $exerciseCounts->pluck('exercise_id');

        $exercises = Exercise::whereIn('id', $exerciseIds)->get()->keyBy('id');

        $result = $exerciseCounts->map(function ($item) use ($exercises) {
            return [
                'exercise_id' => $item->exercise_id,
                'name' => $exercises[$item->exercise_id]->name ?? 'Desconhecido',
                'category' => $exercises[$item->exercise_id]->category ?? null,
                'times_used' => $item->total
            ];
        });

        return response()->json($result);
    }
}
