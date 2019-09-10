<?php

declare(strict_types=1);

namespace App\Presentation\Presenter;

use App\Domain\Presenter\PresenterInterface;
use JsonApiPhp\JsonApi\DataDocument;
use JsonApiPhp\JsonApi\JsonApi;
use JsonApiPhp\JsonApi\Link\SelfLink;
use JsonApiPhp\JsonApi\Meta;
use JsonApiPhp\JsonApi\ResourceCollection;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserListPresenter implements PresenterInterface
{
    use UserPresenterTrait;

    /** @var \App\Domain\Output\UserListOutput */
    private $output;

    public function setOutput($output): void
    {
        $this->output = $output;
    }

    public function getOutput(): DataDocument
    {
        $resources = [];

        foreach ($this->output->users as $user) {
            $resources[] = $this->normalize($user);
        }

        return new DataDocument(
            new ResourceCollection(...$resources),
            new Meta('total', $this->output->total),
            new SelfLink($this->urlGenerator->generate('user_list', [], UrlGeneratorInterface::ABSOLUTE_URL)),
            new JsonApi()
        );
    }
}
