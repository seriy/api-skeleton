<?php

declare(strict_types=1);

namespace App\Presentation\Serializer\Normalizer;

use DateTimeImmutable;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer as BaseNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use function get_class;

class DateTimeNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function normalize($object, $format = null, array $context = []): string
    {
        return (new BaseNormalizer([BaseNormalizer::FORMAT_KEY => DateTimeImmutable::ISO8601]))->normalize($object);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof DateTimeImmutable;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === get_class($this);
    }
}
