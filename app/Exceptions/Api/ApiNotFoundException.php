<?php

namespace App\Exceptions\Api;

use Illuminate\Http\Response;

class ApiNotFoundException extends ApiJsonException
{
    protected int $defaultCode = Response::HTTP_NOT_FOUND;

    protected string $defaultMessage = 'not found';
}
