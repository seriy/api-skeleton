<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use App\Domain\Entity\UserInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class ResetUserPasswordValidationRules implements ValidationRulesInterface
{
    public function getRules(): Constraint
    {
        return new Assert\Collection(['fields' => [
            'data' => new Assert\Collection(['fields' => [
                'type' => new Assert\Required([
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => 'string']),
                    new Assert\EqualTo(['value' => UserInterface::NAME]),
                ]),
                'attributes' => new Assert\Collection(['fields' => [
                    'email' => new Assert\Required([
                        new Assert\NotBlank(),
                        new Assert\Type(['type' => 'string']),
                        new Assert\Length(['min' => 5, 'max' => 191]),
                        new Assert\Email(),
                    ]),
                ]]),
            ]]),
        ]]);
    }
}
