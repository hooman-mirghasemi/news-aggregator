<?php

namespace App\Http\Controllers\Ttaits;

use App\Exceptions\Api\ApiNotFoundException;
use Illuminate\Http\JsonResponse;

trait ApiResponseHelpers
{
    public function respondOk($data = []): JsonResponse
    {
        return $this->responsdJson('ok', $data);
    }

    /**
     * @throws ApiNotFoundException
     */
    public function respondNotFound(string $msg = 'not found ', $data = [], int $customHttpCode = null)
    {
        throw new ApiNotFoundException($data, $msg, $customHttpCode);
    }


    public function respondCustomError(string $msg = 'error', array $data = [], int $code = 400, int $customHttpError = null)
    {
        return response()->json([
            'msg' => $msg,
            'code' => $customHttpError ?? $code,
            'data' => $data,
        ], $code);
    }

    public function responsdJson(string $msg = 'ok', $data = [], int $code = 200, int $customHttpError = null)
    {
        return response()->json([
            'msg' => $msg,
            'code' => $customHttpError ?? $code,
            'data' => $data,
        ], $code);
    }
}
