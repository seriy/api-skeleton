<?php

declare(strict_types=1);

namespace App\Domain\Security;

use function base64_encode;
use function random_bytes;

class PasswordGenerator
{
    /**
     * @throws \Exception
     */
    public function generatePassword(int $entropy = 256): string
    {
        return base64_encode(random_bytes($entropy / 8));
    }
}
