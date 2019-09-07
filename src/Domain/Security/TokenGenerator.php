<?php

declare(strict_types=1);

namespace App\Domain\Security;

use function base64_encode;
use function random_bytes;
use function sha1;

class TokenGenerator
{
    /**
     * @throws \Exception
     */
    public function generateToken(int $entropy = 256): string
    {
        return sha1(base64_encode(random_bytes($entropy / 8)));
    }
}
