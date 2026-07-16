<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskUpdatedResource extends JsonResource
{
    public function __construct()
    {
        parent::__construct(null);
    }

    public function toArray(Request $request): array
    {
        return [
            'message' => 'Task updated successfully.'
        ];
    }
}
