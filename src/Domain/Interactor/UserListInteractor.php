<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Input\UserListInput;
use App\Domain\Output\UserListOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;

class UserListInteractor implements InteractorInterface
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
    public function execute(UserListInput $input, PresenterInterface $presenter): void
    {
        $this->checkUser($input->currentUserId);

        $total = $this->userRepository->getTotalUsers($input->filters);
        $items = $this->userRepository->getUsers(
            $input->filters,
            $input->sorts,
            $input->limit ?: UserRepositoryInterface::ITEMS_PER_REQUEST,
            $input->offset
        );

        $presenter->setOutput(new UserListOutput($total, $items));
    }
}
