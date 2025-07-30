<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkoutRequest;
use App\Http\Requests\UpdateWorkoutRequest;
use App\Services\WorkoutService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\WorkoutResource;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Workouts",
 *     description="Gerenciamento de treinos"
 * )
 */
class WorkoutController extends Controller
{
    public function __construct(private WorkoutService $workoutService) {}

    /**
     * @OA\Get(
     *     path="/api/workouts",
     *     tags={"Workouts"},
     *     summary="Lista os treinos do usuário com filtros e paginação",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="date_start",
     *         in="query",
     *         required=false,
     *         description="Data inicial do treino",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="date_end",
     *         in="query",
     *         required=false,
     *         description="Data final do treino",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         required=false,
     *         description="Categoria do exercício",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Quantidade de resultados por página",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de treinos filtrada com sucesso"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()
            ->workouts()
            ->with(['sets.exercise'])
            ->orderBy('date', 'desc');

        if ($request->filled('date_start')) {
            $query->where('date', '>=', $request->input('date_start'));
        }

        if ($request->filled('date_end')) {
            $query->where('date', '<=', $request->input('date_end'));
        }

        if ($request->filled('category')) {
            $query->whereHas('sets.exercise', function ($q) use ($request) {
                $q->where('category', $request->input('category'));
            });
        }

        $perPage = $request->input('per_page', 10);
        $workouts = $query->paginate($perPage);

        return response()->json([
            'data' => WorkoutResource::collection($workouts->items()),
            'meta' => [
                'current_page' => $workouts->currentPage(),
                'last_page' => $workouts->lastPage(),
                'per_page' => $workouts->perPage(),
                'total' => $workouts->total(),
            ]
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/workouts",
     *     tags={"Workouts"},
     *     summary="Cria um novo treino com séries e exercícios",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"date", "sets"},
     *             @OA\Property(property="date", type="string", format="date", example="2025-07-30"),
     *             @OA\Property(property="notes", type="string", example="Treino de peito"),
     *             @OA\Property(
     *                 property="sets",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="exercise_id", type="integer", example=1),
     *                     @OA\Property(property="weight", type="number", format="float", example=80.0),
     *                     @OA\Property(property="repetitions", type="integer", example=10),
     *                     @OA\Property(property="order", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Treino criado com sucesso"
     *     )
     * )
     */
    public function store(StoreWorkoutRequest $request): JsonResponse
    {
        $user = $request->user();
        $workout = $this->workoutService->store($request->validated(), $user->id);

        return response()->json([
            'message' => 'Treino criado com sucesso',
            'workout' => $workout->load('sets.exercise')
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/workouts/{id}",
     *     tags={"Workouts"},
     *     summary="Retorna os detalhes de um treino específico",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do treino",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do treino retornado com sucesso"
     *     ),
     *     @OA\Response(response=404, description="Treino não encontrado")
     * )
     */
    public function show($id, Request $request): JsonResponse
    {
        $workout = $request->user()
            ->workouts()
            ->with(['sets.exercise'])
            ->findOrFail($id);

        return response()->json(new WorkoutResource($workout));
    }

    /**
     * @OA\Put(
     *     path="/api/workouts/{id}",
     *     tags={"Workouts"},
     *     summary="Atualiza um treino existente",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do treino a ser atualizado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"date", "sets"},
     *             @OA\Property(property="date", type="string", format="date", example="2025-07-30"),
     *             @OA\Property(property="notes", type="string", example="Treino atualizado"),
     *             @OA\Property(
     *                 property="sets",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="exercise_id", type="integer", example=2),
     *                     @OA\Property(property="weight", type="number", format="float", example=60.0),
     *                     @OA\Property(property="repetitions", type="integer", example=12),
     *                     @OA\Property(property="order", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Treino atualizado com sucesso"),
     *     @OA\Response(response=404, description="Treino não encontrado")
     * )
     */
    public function update(UpdateWorkoutRequest $request, $id): JsonResponse
    {
        $workout = $this->workoutService->update($request->validated(), $request->user()->id, $id);

        return response()->json([
            'message' => 'Treino atualizado com sucesso',
            'workout' => $workout
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/workouts/{id}",
     *     tags={"Workouts"},
     *     summary="Remove um treino do usuário",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do treino",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Treino removido com sucesso"),
     *     @OA\Response(response=404, description="Treino não encontrado")
     * )
     */
    public function destroy($id, Request $request): JsonResponse
    {
        $workout = $request->user()
            ->workouts()
            ->findOrFail($id);

        $workout->delete();

        return response()->json(['message' => 'Treino removido com sucesso.']);
    }

    /**
     * @OA\Get(
     *     path="/api/workouts/stats",
     *     tags={"Workouts"},
     *     summary="Retorna estatísticas dos treinos do usuário",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Estatísticas retornadas com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="total_workouts", type="integer", example=25),
     *             @OA\Property(
     *                 property="load_per_exercise",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="exercise_id", type="integer", example=1),
     *                     @OA\Property(property="exercise_name", type="string", example="Agachamento"),
     *                     @OA\Property(property="total_load", type="number", format="float", example=1250.5)
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="weekly_frequency",
     *                 type="object",
     *                 additionalProperties=@OA\Schema(type="integer", example=3),
     *                 example={"2025-07-01": 2, "2025-07-08": 3}
     *             )
     *         )
     *     )
     * )
     */
    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();

        $workouts = $user->workouts()->with('sets.exercise')->get();

        $totalWorkouts = $workouts->count();

        $exerciseStats = [];

        foreach ($workouts as $workout) {
            foreach ($workout->sets as $set) {
                $id = $set->exercise_id;
                $name = $set->exercise->name;

                $total = ($set->weight ?? 0) * ($set->repetitions ?? 0);

                if (!isset($exerciseStats[$id])) {
                    $exerciseStats[$id] = [
                        'exercise_id' => $id,
                        'exercise_name' => $name,
                        'total_load' => 0
                    ];
                }

                $exerciseStats[$id]['total_load'] += $total;
            }
        }

        $last30Days = now()->subDays(30);
        $frequency = $user->workouts()
            ->where('date', '>=', $last30Days)
            ->get()
            ->groupBy(fn($workout) => \Carbon\Carbon::parse($workout->date)->startOfWeek()->format('Y-m-d'))
            ->map(fn($group) => $group->count());

        return response()->json([
            'total_workouts' => $totalWorkouts,
            'load_per_exercise' => array_values($exerciseStats),
            'weekly_frequency' => $frequency
        ]);
    }
}
