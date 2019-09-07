<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use Symfony\Component\Validator\Constraint;

interface ValidationRulesInterface
{
    public const MAX_INT = 4294967295;
    public const MAX_LIMIT = 50;

    public function getRules(): Constraint;
}
