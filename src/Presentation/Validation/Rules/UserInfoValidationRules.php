<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class UserInfoValidationRules implements ValidationRulesInterface
{
    public function getRules(): Constraint
    {
        return new Assert\Collection(['fields' => [
            'userId' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'string']),
                new Assert\Range(['min' => 1, 'max' => self::MAX_INT]),
            ]),
        ]]);
    }
}
