<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Input\UploadFileInput;
use App\Domain\Output\UploadFileOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;

class UploadFileInteractor implements InteractorInterface
{
    use UserCheckerTrait;

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \App\Domain\Exception\DomainException
     */
    public function execute(UploadFileInput $input, PresenterInterface $presenter): void
    {
        $this->checkUser($input->currentUserId);

        $presenter->setOutput(new UploadFileOutput(count($input->files), $input->files));
    }
}
