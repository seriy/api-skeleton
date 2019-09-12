<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class ResetUserPasswordValidationRules implements ValidationRulesInterface
{
    public function getRules(): Constraint
    {
        return new Assert\Collection(['fields' => [
            'data' => new Assert\Collection(['fields' => [
                'type' => new Assert\Required(UserValidationRules::getTypeRules()),
                'attributes' => new Assert\Collection(['fields' => [
                    'email' => new Assert\Required(UserValidationRules::getEmailRules()),
                ]]),
            ]]),
        ]]);
    }
}
