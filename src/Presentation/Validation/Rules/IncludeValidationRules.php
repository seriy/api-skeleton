<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use function array_unique;

class IncludeValidationRules
{
    public function getRules(array $fields = []): Constraint
    {
        return new Assert\Optional([
            new Assert\Type(['type' => 'array']),
            new Assert\All(new Assert\Type(['type' => 'string'])),
            new Assert\Choice(['choices' => array_unique($fields), 'multiple' => true]),
        ]);
    }
}
