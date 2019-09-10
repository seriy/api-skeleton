<?php

declare(strict_types=1);

namespace App\Presentation\Presenter;

use JsonApiPhp\JsonApi\Error;
use JsonApiPhp\JsonApi\ErrorDocument;
use JsonApiPhp\JsonApi\JsonApi;
use Symfony\Component\Validator\ConstraintViolationListInterface;

trait ConstraintViolationListPresenterTrait
{
    public function normalize(ConstraintViolationListInterface $violations): ErrorDocument
    {
        $errors = [new JsonApi()];

        foreach ($violations as $violation) {
            $name = $this->getName($violation->getPropertyPath());

            $members = [
                new Error\Detail(str_replace(
                    ['This value', 'This field', 'This collection', 'The value you selected'],
                    ucfirst($name),
                    $violation->getMessage()
                )),
            ];

            if ($this->isParameter($violation->getPropertyPath())) {
                $members[] = new Error\Title('Invalid Parameter');
                $members[] = new Error\SourceParameter($name);
            } else {
                $members[] = new Error\Title('Invalid Attribute');
                $members[] = new Error\SourcePointer($this->getPointer($violation->getPropertyPath()));
            }

            $errors[] = new Error(...$members);
        }

        return new ErrorDocument(...$errors);
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
}
