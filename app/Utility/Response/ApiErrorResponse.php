<?php

declare(strict_types=1);

namespace App\Utility\Response;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use stdClass;

class ApiErrorResponse implements Responsable
{
    public function __construct(

        public string $message,
        public int $code,
        public array $headers = [],
    ) {}

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'data' => new stdClass,
            'message' => $this->message,
        ], $this->code, $this->headers);
    }
}
