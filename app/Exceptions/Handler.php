<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Http\Responses\ResponseFactory;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (ApiValidationException $e) {
            return $e->getResponse();
        });
        $this->renderable(function (CategoryNotFoundException $e) {
            return ResponseFactory::createDataError(
                $e->getMessage(),
                Response::HTTP_NOT_FOUND,
            );
        });
        $this->renderable(function (TaskNotFoundException $e) {
            return ResponseFactory::createDataError(
                $e->getMessage(),
                Response::HTTP_NOT_FOUND,
            );
        });
//        $this->renderable(function (Throwable $e) {
//            return ResponseFactory::createDataError(
//                'Internal server error. Try later.',
//                Response::HTTP_INTERNAL_SERVER_ERROR,
//            );
//        });
    }
}
