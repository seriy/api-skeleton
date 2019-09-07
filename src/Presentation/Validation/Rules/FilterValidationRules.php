<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class FilterValidationRules
{
    public function getRules(array $fields = []): Constraint
    {
        $constraints = [];

        foreach ($fields as $field) {
            $constraints[$field] = new Assert\Optional([
                new Assert\Type(['type' => 'array']),
                new Assert\All(new Assert\Type(['type' => 'string'])),
            ]);
        }

        return new Assert\Optional(new Assert\Collection($constraints));
    }
}
