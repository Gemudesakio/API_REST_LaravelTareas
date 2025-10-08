<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Tag;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();

        $query = Task::query()
            ->where('user_id', $user->id)
            ->with('tags');

        // Filtros
        if ($status = $request->query('status')) {
            $query->where('is_completed', $status === 'completed');
        }
        if ($q = $request->query('q')) {
            $query->where(function($qBuilder) use ($q) {
                $qBuilder->where('title', 'like', "%{$q}%")
                         ->orWhere('description', 'like', "%{$q}%");
            });
        }
        if ($tag = $request->query('tag')) {
            $query->whereHas('tags', fn($t) => $t->where('name', $tag));
        }

        // Orden
        $sort = $request->query('sort', '-created_at'); // ej: created_at | -created_at
        $dir  = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $col  = ltrim($sort, '-');
        if (!in_array($col, ['created_at','title','completed_at'])) {
            $col = 'created_at';
        }
        $query->orderBy($col, $dir);

        // Paginación
        $perPage = (int) $request->query('per_page', 10);
        $perPage = max(1, min($perPage, 100));

        $page = $query->paginate($perPage);

        return response()->json([
            'success'=> true,
            'data'   => TaskResource::collection($page->items()),
            'meta'   => [
                'current_page' => $page->currentPage(),
                'per_page'     => $page->perPage(),
                'total'        => $page->total(),
                'last_page'    => $page->lastPage(),
            ],
        ]);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $user = Auth::user();
        $data = $request->validated();

        $task = Task::create([
            'user_id'     => $user->id,
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'is_completed'=> $data['is_completed'] ?? false,
            'completed_at'=> !empty($data['is_completed']) ? now() : null,
        ]);

        if (!empty($data['tags'])) {
            $tagIds = collect($data['tags'])->map(
                fn($name) => Tag::firstOrCreate(['name'=>$name])->id
            );
            $task->tags()->sync($tagIds);
        }

        return response()->json([
            'success'=> true,
            'data'   => new TaskResource($task->load('tags'))
        ], 201);
    }

    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);
        return response()->json(['success'=>true,'data'=> new TaskResource($task->load('tags'))]);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);
        $data = $request->validated();

        $task->fill([
            'title'       => $data['title']        ?? $task->title,
            'description' => $data['description']  ?? $task->description,
        ]);

        if (array_key_exists('is_completed', $data)) {
            $task->is_completed = (bool)$data['is_completed'];
            $task->completed_at = $task->is_completed ? now() : null;
        }

        $task->save();

        if (array_key_exists('tags',$data)) {
            $tagIds = collect($data['tags'])->map(
                fn($name) => Tag::firstOrCreate(['name'=>$name])->id
            );
            $task->tags()->sync($tagIds);
        }

        return response()->json(['success'=>true,'data'=> new TaskResource($task->load('tags'))]);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);
        $task->delete();

        return response()->json([], 204);
    }

    public function toggle(Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $task->is_completed = !$task->is_completed;
        $task->completed_at = $task->is_completed ? now() : null;
        $task->save();

        return response()->json(['success'=>true,'data'=> new TaskResource($task)]);
    }
}
