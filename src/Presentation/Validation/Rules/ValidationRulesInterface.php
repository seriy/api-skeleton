<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use Symfony\Component\Validator\Constraint;

interface ValidationRulesInterface
{
    public const MAX_INT = 4294967295;
    public const MAX_LIMIT = 50;
    public const MAX_FILE_SIZE = 20 * 1024 * 1024; // 20 mb
    public const MAX_FILE_COUNT = 10;
    public const MAX_FILTER_COUNT = 5;
    public const MAX_SORT_COUNT = 5;

    public function getRules(): Constraint;
}
