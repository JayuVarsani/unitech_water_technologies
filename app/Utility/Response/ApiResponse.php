<?php

declare(strict_types=1);

namespace App\Utility\Response;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse implements Responsable
{
    public function __construct(
        public mixed $data = null,
        public string $message = 'OK',
        public int $code = Response::HTTP_OK,
        public array $headers = [],
    ) {}

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'data' => $this->data ?? new stdClass,
            'message' => ucwords($this->message),
        ], $this->code, $this->headers);
    }
}
