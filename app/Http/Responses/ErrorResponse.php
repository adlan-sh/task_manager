<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResponse extends JsonResponse
{
    public function __construct(
        null|string|array|JsonResource $data = null,
        int $statusCode = 400,
    ) {
        $dataResponse = [
            'success' => false,
        ];

        if ($data instanceof JsonResource) {
            $dataResponse['data'] = $data;
        }
        elseif (is_array($data)) {
            if (isset($data['success'])) {
                unset($data['success']);
            }
            $dataResponse['errors'] = $data;
        }
        elseif (is_string($data)) {
            $dataResponse['message'] = $data;
        }

        parent::__construct(
            $dataResponse,
            $statusCode,
            options: JSON_UNESCAPED_UNICODE,
        );
    }
}
