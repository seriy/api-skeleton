<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use App\Domain\Entity\UserInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ChangeUserPhotoValidationRules implements ValidationRulesInterface
{
    private $userId;

    public function getRules(): Constraint
    {
        return new Assert\Collection(['fields' => [
            'userId' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'string']),
                new Assert\Range(['min' => 1, 'max' => self::MAX_INT]),
                new Assert\Callback(['callback' => function ($userId) {
                    $this->userId = $userId;
                }]),
            ]),
            'data' => new Assert\Collection(['fields' => [
                'type' => new Assert\Required([
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => 'string']),
                    new Assert\EqualTo(['value' => UserInterface::NAME]),
                ]),
                'id' => new Assert\Required([
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => 'string']),
                    new Assert\Range(['min' => 1, 'max' => self::MAX_INT]),
                    new Assert\Callback(['callback' => function ($id, ExecutionContextInterface $context) {
                        if ($this->userId !== $id) {
                            $context->addViolation('User IDs does not equals.');
                        }
                    }]),
                ]),
                'attributes' => new Assert\Collection(['fields' => [
                    'photo' => new Assert\Required([
                        new Assert\NotBlank(),
                        new Assert\Type(['type' => 'string']),
                        new Assert\Length(['min' => 5, 'max' => 191]),
                    ]),
                ]]),
            ]]),
        ]]);
    }
}
