<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use function array_unique;

class FieldsValidationRules
{
    public function getRules(array $fields = []): Constraint
    {
        $constraints = [];

        foreach ($fields as $entity => $values) {
            $constraints[$entity] = new Assert\Optional([
                new Assert\Type(['type' => 'array']),
                new Assert\All(new Assert\Type(['type' => 'string'])),
                new Assert\Choice(['choices' => array_unique($values), 'multiple' => true]),
            ]);
        }

        return new Assert\Optional(new Assert\Collection($constraints));
    }
}
