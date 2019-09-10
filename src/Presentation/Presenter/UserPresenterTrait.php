<?php

declare(strict_types=1);

namespace App\Presentation\Presenter;

use App\Domain\Entity\UserInterface;
use DateTimeImmutable;
use JsonApiPhp\JsonApi\Attribute;
use JsonApiPhp\JsonApi\Link\SelfLink;
use JsonApiPhp\JsonApi\ResourceObject;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

trait UserPresenterTrait
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function normalize(UserInterface $user): ResourceObject
    {
        return new ResourceObject(
            UserInterface::NAME,
            (string) $user->getId(),
            new Attribute('email', $user->getEmail()),
            new Attribute('username', $user->getUsername()),
            new Attribute('emailConfirmed', $user->isEmailConfirmed()),
            new Attribute('createdAt', $user->getCreatedAt()->format(DateTimeImmutable::ISO8601)),
            new SelfLink($this->urlGenerator->generate(
                'user_info',
                ['userId' => $user->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
            )),
        );
    }
}
