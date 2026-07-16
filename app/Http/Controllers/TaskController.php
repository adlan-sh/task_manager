<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCreatedResource;
use App\Http\Resources\TaskDeletedResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskUpdatedResource;
use App\Http\Responses\ResponseFactory;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService)
    {}

    public function getById(int $id): JsonResponse
    {
        $task = $this->taskService->getById($id);

        return ResponseFactory::createDataSuccess(new TaskResource($task));
    }

    public function getAll(Request $request): JsonResponse
    {
        $tasks = $this->taskService->getAll($request);

        return ResponseFactory::createDataSuccess(TaskResource::collection($tasks));
    }

    public function create(CreateTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create($request);

        return ResponseFactory::createDataSuccess(new TaskCreatedResource($task));
    }

    public function update(int $id, UpdateTaskRequest $request): JsonResponse
    {
        $this->taskService->update($id, $request);

        return ResponseFactory::createDataSuccess(new TaskUpdatedResource());
    }

    public function delete(int $id): JsonResponse
    {
        $this->taskService->delete($id);

        return ResponseFactory::createDataSuccess(new TaskDeletedResource());
    }
}
