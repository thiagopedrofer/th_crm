<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Http\Response;

class UnauthorizedException extends UnauthorizedHttpException
{
    public function __construct(string $message = 'Unauthorized', ?\Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct('Bearer', $message, $previous, $code, $headers);
    }

    public function render()
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], Response::HTTP_UNAUTHORIZED);
    }
}