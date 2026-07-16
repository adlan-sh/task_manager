<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Http\Responses\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class ApiValidationException extends ValidationException
{
    private array $errors;

    public function __construct(Validator $validator)
    {
        $this->errors = $validator->errors()->messages();
        parent::__construct($validator, $this->getResponse());
    }

    public function getResponse(): JsonResponse
    {
        return ResponseFactory::createDataError($this->errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
