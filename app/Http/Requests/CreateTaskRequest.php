<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enum\TaskPriority;
use App\Exceptions\ApiValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'string',
            'due_date' => 'required|date',
            'priority' => [
                'required',
                Rule::in(TaskPriority::cases())
            ],
            'category_id' => 'required|int'
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new ApiValidationException($validator);
    }
}
