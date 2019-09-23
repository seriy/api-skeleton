<?php

declare(strict_types=1);

namespace App\Presentation\Error;

class AuthenticationError
{
    public const KEY_NOT_FOUND = [
        'code' => 1,
        'status' => 401,
        'message' => 'Key not found.',
    ];
    public const KEY_INVALID = [
        'code' => 2,
        'status' => 401,
        'message' => 'Key is invalid.',
    ];
    public const BAD_CREDENTIALS = [
        'code' => 3,
        'status' => 401,
        'message' => 'Bad credentials.',
    ];
    public const JWT_NOT_FOUND = [
        'code' => 4,
        'status' => 401,
        'message' => 'JWT not found.',
    ];
    public const JWT_INVALID = [
        'code' => 5,
        'status' => 401,
        'message' => 'JWT is invalid.',
    ];
    public const JWT_EXPIRED = [
        'code' => 6,
        'status' => 401,
        'message' => 'JWT is expired.',
    ];
    public const AUTHORIZATION_CODE_NOT_FOUND = [
        'code' => 7,
        'status' => 401,
        'message' => 'Authorization code not found.',
    ];
    public const PROVIDER_NOT_SUPPORTED = [
        'code' => 8,
        'status' => 400,
        'message' => '\'%s\' provider is not supported.',
    ];
}
