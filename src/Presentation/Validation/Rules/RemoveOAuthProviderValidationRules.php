<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RemoveOAuthProviderValidationRules implements ValidationRulesInterface
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
            'provider' => UserValidationRules::getProviderRules(),
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
            ]]),
        ]]);
    }
}
