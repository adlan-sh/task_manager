<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

class ResponseFactory
{
    public static function createSuccess(int $statusCode = 200): SuccessResponse
    {
        return new SuccessResponse(statusCode: $statusCode);
    }

    public static function createDataSuccess(string|array|JsonResource $data, int $statusCode = 200): SuccessResponse
    {
        return new SuccessResponse($data, $statusCode);
    }

    public static function createError(int $statusCode = 400): ErrorResponse
    {
        return new ErrorResponse(statusCode: $statusCode);
    }

    public static function createDataError(string|array|JsonResource $data, int $statusCode = 400): ErrorResponse
    {
        return new ErrorResponse($data, $statusCode);
    }
}
