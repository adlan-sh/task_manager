<?php

declare(strict_types=1);

namespace App\Services;

use App\Enum\TaskStatus;
use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\TaskNotFoundException;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Category;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class TaskService
{
    private const int PER_PAGE = 10;

    public function getById(int $id): ?Task
    {
        $task = Task::query()->find($id);

        if (null === $task) {
            throw new TaskNotFoundException();
        }

        return $task;
    }

    public function getAll(Request $request): LengthAwarePaginator
    {
        $page = $request->query('page', 1);
        $sort = $request->query('sort', 'due_date');
        $search = $request->query('search');

        $query = Task::query()->orderBy($sort);

        if ($search !== null) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->paginate(self::PER_PAGE, page: $page);
    }

    public function create(CreateTaskRequest $request): Task
    {
        $category_id = $request->input('category_id');
        $category = Category::query()->find($category_id);

        if ($category === null) {
            throw new CategoryNotFoundException();
        }

        return Task::query()->create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'due_date' => new Carbon($request->input('due_date')),
            'created_at' => Carbon::now(),
            'status' => TaskStatus::NOT_DONE,
            'priority' => $request->input('priority'),
            'category_id' => $category_id,
        ]);
    }

    public function update(int $id, UpdateTaskRequest $request): Task
    {
        $task = Task::query()->find($id);

        if ($task === null) {
            throw new TaskNotFoundException();
        }

        $category_id = $request->input('category_id');
        $category = Category::query()->find($category_id);

        if ($category === null) {
            throw new CategoryNotFoundException();
        }

        $task->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'due_date' => new Carbon($request->input('due_date')),
            'status' => $request->input('status'),
            'priority' => $request->input('priority'),
            'category_id' => $category_id,
        ]);

        $task->save();

        return $task;
    }

    public function delete(int $id): bool
    {
        $task = Task::query()->find($id);

        if ($task === null) {
            throw new TaskNotFoundException();
        }

        return $task->delete();
    }
}
