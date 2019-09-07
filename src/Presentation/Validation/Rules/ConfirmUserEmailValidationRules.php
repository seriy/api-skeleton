<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class ConfirmUserEmailValidationRules implements ValidationRulesInterface
{
    public function getRules(): Constraint
    {
        return new Assert\Collection(['fields' => [
            'token' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'string']),
                new Assert\Length(['min' => 40, 'max' => 40]),
            ]),
        ]]);
    }
}
