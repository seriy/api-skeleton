<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Error\UserError;
use App\Domain\Exception\DomainException;
use App\Domain\Input\RemoveOAuthProviderInput;
use App\Domain\Output\RemoveOAuthProviderOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use function mb_strtolower;
use function method_exists;
use function ucfirst;

class RemoveOAuthProviderInteractor implements InteractorInterface
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
    public function execute(RemoveOAuthProviderInput $input, PresenterInterface $presenter): void
    {
        $user = $this->checkUser($input->currentUserId);

        if ($input->currentUserId !== $input->userId) {
            throw new DomainException(UserError::PERMISSION_DENIED);
        }

        $method = 'set'.ucfirst(mb_strtolower($input->provider)).'Id';
        if (!method_exists($user, $method)) {
            throw new DomainException(UserError::PROVIDER_NOT_SUPPORTED, [$input->provider]);
        }

        $user->{$method}(null);
        $this->userRepository->saveUser($user);

        $presenter->setOutput(new RemoveOAuthProviderOutput($user));
    }
}
