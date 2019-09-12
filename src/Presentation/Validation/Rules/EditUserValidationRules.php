<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class EditUserValidationRules implements ValidationRulesInterface
{
    private $userId;

    public function getRules(): Constraint
    {
        return new Assert\Collection(['fields' => [
            'userId' => new Assert\Required(array_merge(
                UserValidationRules::getIdRules(),
                [new Assert\Callback(['callback' => function ($userId) {
                    $this->userId = $userId;
                }])],
            )),
            'data' => new Assert\Collection(['fields' => [
                'type' => new Assert\Required(UserValidationRules::getTypeRules()),
                'id' => new Assert\Required(array_merge(
                    UserValidationRules::getIdRules(),
                    [new Assert\Callback(['callback' => function ($id, ExecutionContextInterface $context) {
                        if ($this->userId !== $id) {
                            $context->addViolation('User IDs does not equals.');
                        }
                    }])],
                )),
                'attributes' => new Assert\Collection(['fields' => [
                    'email' => new Assert\Required(UserValidationRules::getEmailRules()),
                    'username' => new Assert\Required(UserValidationRules::getUsernameRules()),
                    'firstName' => new Assert\Optional(UserValidationRules::getFirstNameRules()),
                    'lastName' => new Assert\Optional(UserValidationRules::getLastNameRules()),
                ]]),
            ]]),
        ]]);
    }
}
