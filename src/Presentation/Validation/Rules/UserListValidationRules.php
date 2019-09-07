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
            'filter' => (new FilterValidationRules())->getRules(['id', 'username']),
            'page' => (new PageValidationRules())->getRules(),
            'sort' => (new SortValidationRules())->getRules(['id', 'username']),
        ]]);
    }
}
