<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Task
 */
class TaskResource extends JsonResource
{
    public function __construct(Task $task)
    {
        parent::__construct($task);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'priority' => $this->priority,
            'category_id' => $this->category_id,
            'category' => $this->category->name,
        ];
    }
}
