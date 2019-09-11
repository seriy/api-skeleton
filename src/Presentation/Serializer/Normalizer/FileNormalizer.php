<?php

declare(strict_types=1);

namespace App\Presentation\Serializer\Normalizer;

use App\Domain\Entity\FileInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use function get_class;

class FileNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function normalize($object, $format = null, array $context = []): array
    {
        /** @var \App\Domain\Entity\FileInterface $file */
        $file = $object;

        return [
            'type' => FileInterface::NAME,
            'id' => (string) $file->getOriginalName(),
            'attributes' => [
                'path' => $file->getPath(),
            ],
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof FileInterface;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === get_class($this);
    }
}
