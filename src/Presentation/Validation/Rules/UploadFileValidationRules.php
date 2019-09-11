<?php

declare(strict_types=1);

namespace App\Presentation\Validation\Rules;

use App\Domain\Entity\FileInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class UploadFileValidationRules implements ValidationRulesInterface
{
    public function getRules(): Constraint
    {
        return new Assert\Collection(['fields' => [
            'file' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'array']),
                new Assert\Count(['max' => self::MAX_FILE_COUNT]),
                new Assert\All(new Assert\File([
                    'maxSize' => self::MAX_FILE_SIZE,
                    'mimeTypes' => FileInterface::IMAGE_MIME_TYPES,
                ])),
            ]),
        ]]);
    }
}
