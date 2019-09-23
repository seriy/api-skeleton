<?php

declare(strict_types=1);

namespace App\Domain\Security;

use function mb_strpos;
use function mb_substr;
use function preg_replace;
use function random_int;
use function trim;

class UsernameGenerator
{
    /**
     * @throws \Exception
     */
    public function generateUsername(string $base, bool $unique = false): string
    {
        if (false !== $position = mb_strpos($base, '@')) {
            $base = mb_substr($base, 0, $position);
        }

        $base = preg_replace('/[^a-zA-Z0-9]/', '', $base);

        return trim($base).($unique ? random_int(1, 99) : '');
    }
}
