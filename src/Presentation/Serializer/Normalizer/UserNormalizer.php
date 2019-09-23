<?php

declare(strict_types=1);

namespace App\Presentation\Serializer\Normalizer;

use App\Domain\Entity\UserInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use function get_class;

class UserNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function normalize($object, $format = null, array $context = []): array
    {
        /** @var \App\Domain\Entity\UserInterface $user */
        $user = $object;

        return [
            'type' => UserInterface::NAME,
            'id' => (string) $user->getId(),
            'attributes' => [
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'fullName' => $user->getFullName(),
                'photo' => $user->getPhoto(),
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'emailConfirmed' => $user->isEmailConfirmed(),
                'createdAt' => (new DateTimeNormalizer())->normalize($user->getCreatedAt()),
            ],
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof UserInterface;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === get_class($this);
    }
}
