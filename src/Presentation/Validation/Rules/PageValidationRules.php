<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class PageValidationRules
{
    public function getRules(): Constraint
    {
        return new Assert\Optional(new Assert\Collection(['fields' => [
            'limit' => new Assert\Optional([
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'string']),
                new Assert\Range(['min' => 1, 'max' => ValidationRulesInterface::MAX_LIMIT]),
            ]),
            'offset' => new Assert\Optional([
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'string']),
                new Assert\Range(['min' => 0, 'max' => ValidationRulesInterface::MAX_INT]),
            ]),
        ]]));
    }
}
