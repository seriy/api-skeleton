<?php

declare(strict_types=1);

namespace App\Domain\Error;

class UserError
{
    public const EMAIL_TAKEN = [
        'code' => 1000,
        'status' => 409,
        'message' => 'Email \'%s\' is already taken.',
    ];
    public const USERNAME_TAKEN = [
        'code' => 1001,
        'status' => 409,
        'message' => 'Username \'%s\' is already taken.',
    ];
    public const USER_NOT_FOUND = [
        'code' => 1002,
        'status' => 404,
        'message' => 'User with ID \'%d\' not found.',
    ];
    public const USER_BLOCKED = [
        'code' => 1003,
        'status' => 403,
        'message' => 'User with ID \'%d\' is blocked.',
    ];
    public const USER_DELETED = [
        'code' => 1004,
        'status' => 403,
        'message' => 'User with ID \'%d\' is deleted.',
    ];
    public const PASSWORD_INCORRECT = [
        'code' => 1005,
        'status' => 400,
        'message' => 'Password is incorrect.',
    ];
    public const EMAIL_CONFIRMATION_TOKEN_INCORRECT = [
        'code' => 1006,
        'status' => 400,
        'message' => 'Token \'%s\' is incorrect.',
    ];
    public const EMAIL_CONFIRMED = [
        'code' => 1007,
        'status' => 409,
        'message' => 'Email \'%s\' is already confirmed.',
    ];
    public const EMAIL_CONFIRMATION_TOKEN_EXPIRED = [
        'code' => 1008,
        'status' => 403,
        'message' => 'Token \'%s\' is expired.',
    ];
    public const PASSWORD_RESETTING_TOKEN_INCORRECT = [
        'code' => 1009,
        'status' => 400,
        'message' => 'Token \'%s\' is incorrect.',
    ];
    public const PASSWORD_RESETTING_TOKEN_EXPIRED = [
        'code' => 1010,
        'status' => 403,
        'message' => 'Token \'%s\' is expired.',
    ];
    public const PERMISSION_DENIED = [
        'code' => 1011,
        'status' => 403,
        'message' => 'You do not have enough rights.',
    ];
    public const CANNOT_BLOCK_YOURSELF = [
        'code' => 1012,
        'status' => 400,
        'message' => 'You cannot block yourself.',
    ];
    public const CANNOT_UNBLOCK_YOURSELF = [
        'code' => 1013,
        'status' => 400,
        'message' => 'You cannot unblock yourself.',
    ];
    public const EMAIL_NOT_FOUND = [
        'code' => 1014,
        'status' => 404,
        'message' => 'User with email \'%s\' not found.',
    ];
    public const USER_ACTIVE = [
        'code' => 1015,
        'status' => 409,
        'message' => 'User with ID \'%d\' is active.',
    ];
}
