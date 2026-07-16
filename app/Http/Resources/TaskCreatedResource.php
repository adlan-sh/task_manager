<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Task */
class TaskCreatedResource extends JsonResource
{
    public function __construct(Task $task)
    {
        parent::__construct($task);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'message' => 'Task created successfully.'
        ];
    }
}
