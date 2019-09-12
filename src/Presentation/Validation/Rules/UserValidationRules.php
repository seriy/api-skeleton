<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use App\Domain\Entity\UserInterface;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserValidationRules
{
    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public static function getTypeRules(): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Type(['type' => 'string']),
            new Assert\EqualTo(['value' => UserInterface::NAME]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public static function getIdRules(): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Type(['type' => 'string']),
            new Assert\Range(['min' => 1, 'max' => ValidationRulesInterface::MAX_INT]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public static function getEmailRules(): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Type(['type' => 'string']),
            new Assert\Length(['min' => 5, 'max' => 191]),
            new Assert\Email(),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public static function getUsernameRules(): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Type(['type' => 'string']),
            new Assert\Length(['min' => 1, 'max' => 40]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public static function getPasswordRules(): array
    {
        return self::getResettingTokenRules();
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public static function getFirstNameRules(): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Type(['type' => 'string']),
            new Assert\Length(['min' => 1, 'max' => 40]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public static function getLastNameRules(): array
    {
        return self::getFirstNameRules();
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public static function getConfirmationTokenRules(): array
    {
        return self::getResettingTokenRules();
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public static function getResettingTokenRules(): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Type(['type' => 'string']),
            new Assert\Length(['min' => 40, 'max' => 40]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public static function getPhotoRules(): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Type(['type' => 'string']),
            new Assert\Length(['min' => 5, 'max' => 191]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public static function getDeviceTokenRules(): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Type(['type' => 'string']),
            new Assert\Length(['min' => 64, 'max' => 191]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public static function getBlockedToRules(): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\DateTime(),
            new Assert\Callback(['callback' => function ($blockedTo, ExecutionContextInterface $context) {
                if (new DateTimeImmutable($blockedTo) < new DateTimeImmutable()) {
                    $context->addViolation('BlockedTo should be greater than "today".');
                }
            }]),
        ];
    }
}
