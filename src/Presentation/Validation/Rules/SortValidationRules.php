<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use function array_unique;

class SortValidationRules
{
    public function getRules(array $fields = []): Constraint
    {
        $choices = [];

        foreach ($fields as $field) {
            $choices[] = $field;
            $choices[] = '-'.$field;
        }

        return new Assert\Optional([
            new Assert\NotBlank(),
            new Assert\Type(['type' => 'array']),
            new Assert\Count(['max' => ValidationRulesInterface::MAX_SORT_COUNT]),
            new Assert\All(new Assert\Type(['type' => 'string'])),
            new Assert\Choice(['choices' => array_unique($choices), 'multiple' => true]),
        ]);
    }
}
