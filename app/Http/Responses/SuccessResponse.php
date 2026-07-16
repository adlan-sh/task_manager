<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResponse extends JsonResponse
{
    public function __construct(
        null|string|array|JsonResource $data = null,
        int $statusCode = 200,
    ) {
        $dataResponse = [
            'success' => true,
        ];

        if ($data instanceof JsonResource || is_array($data)) {
            if (isset($data['success'])) {
                unset($data['success']);
            }
            $dataResponse['data'] = $data;
        } elseif (is_string($data)) {
            $dataResponse['message'] = $data;
        }

        parent::__construct(
            $dataResponse,
            $statusCode,
            options: JSON_UNESCAPED_UNICODE,
        );
    }
}
