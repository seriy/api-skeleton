<?php

declare(strict_types=1);

namespace App\Presentation\Presenter;

use App\Domain\Presenter\PresenterInterface;
use JsonApiPhp\JsonApi\DataDocument;
use JsonApiPhp\JsonApi\JsonApi;
use JsonApiPhp\JsonApi\Link\SelfLink;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RecoverUserPresenter implements PresenterInterface
{
    use UserPresenterTrait;

    /** @var \App\Domain\Output\RecoverUserOutput */
    private $output;

    public function setOutput($output): void
    {
        $this->output = $output;
    }

    public function getOutput(): DataDocument
    {
        return new DataDocument(
            $this->normalize($this->output->user),
            new SelfLink($this->urlGenerator->generate(
                'recover_user',
                ['userId' => $this->output->user->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
            )),
            new JsonApi()
        );
    }
}
