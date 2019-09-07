<?php

declare(strict_types=1);

namespace App\Domain\Input;

final class UserListInput implements InputInterface
{
    public $currentUserId;
    public $filters;
    public $sorts;
    public $limit;
    public $offset;

    public function __construct(int $currentUserId, array $filters, array $sorts, int $limit, int $offset)
    {
        $this->currentUserId = $currentUserId;
        $this->filters = $filters;
        $this->sorts = $sorts;
        $this->limit = $limit;
        $this->offset = $offset;
    }
}
