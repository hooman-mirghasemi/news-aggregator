<?php

namespace App\Exceptions\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiJsonException extends Exception
{
    protected string $defaultMessage = '';

    protected int $defaultCode = Response::HTTP_BAD_REQUEST;

    protected int $customHttpErrorCode = Response::HTTP_BAD_REQUEST;

    protected $data = [];

    public function __construct($data = [], $message = '', int $customHttpErrorCode = null, int $code = null)
    {
        $this->data = $data;
        $this->code = $code ?? $this->defaultCode;
        $this->customHttpErrorCode = $customHttpErrorCode ?? $this->code;
        $this->message = $message ?? $this->defaultMessage;
    }

    public function render(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'msg' => $this->message,
            'code' => $this->customHttpErrorCode ?? $this->code,
            'data' => $this->data,
        ], $this->code);
    }
}
