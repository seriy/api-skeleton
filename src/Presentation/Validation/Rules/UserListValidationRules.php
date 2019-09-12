<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class UserListValidationRules implements ValidationRulesInterface
{
    public function getRules(): Constraint
    {
        return new Assert\Collection(['fields' => [
            'filter' => FilterValidationRules::getRules(['id', 'username']),
            'page' => PageValidationRules::getRules(),
            'sort' => SortValidationRules::getRules(['id', 'username']),
        ]]);
    }
}
