<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;
use Throwable;
use function sprintf;

class DomainException extends Exception implements ExceptionInterface
{
    private $statusCode;

    public function __construct(array $error, array $placeholders = [], Throwable $previous = null)
    {
        $this->statusCode = $error['status'];
        $message = sprintf($error['message'], ...$placeholders);

        parent::__construct($message, $error['code'], $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
