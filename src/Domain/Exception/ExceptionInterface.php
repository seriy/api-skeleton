<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Throwable;

interface ExceptionInterface extends Throwable
{
    public function getStatusCode(): int;
}
