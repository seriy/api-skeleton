<?php

declare(strict_types=1);

namespace App\Presentation\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use function array_pop;
use function explode;
use function get_class;
use function mb_strpos;
use function str_replace;
use function trim;
use function ucfirst;

class ConstraintViolationListNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function normalize($object, $format = null, array $context = []): array
    {
        $errors = [];

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $violation */
        foreach ($object as $violation) {
            $name = $this->getName($violation->getPropertyPath());

            $error = [
                'detail' => str_replace(
                    ['This value', 'This field', 'This collection', 'The value you selected'],
                    ucfirst($name),
                    $violation->getMessage()
                ),
            ];

            if ($this->isParameter($violation->getPropertyPath())) {
                $error['title'] = 'Invalid Parameter';
                $error['source'] = ['parameter' => $name];
            } else {
                $error['title'] = 'Invalid Attribute';
                $error['source'] = ['pointer' => $this->getPointer($violation->getPropertyPath())];
            }

            $errors[] = $error;
        }

        return $errors;
    }

    private function getName(string $propertyPath): string
    {
        $path = explode('/', $this->getPointer($propertyPath));

        return array_pop($path);
    }

    private function getPointer(string $propertyPath): string
    {
        return '/'.trim(str_replace('][', '/', $propertyPath), '[]');
    }

    private function isParameter(string $propertyPath): bool
    {
        return 0 !== mb_strpos($this->getPointer($propertyPath), '/data');
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof ConstraintViolationListInterface;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === get_class($this);
    }
}
