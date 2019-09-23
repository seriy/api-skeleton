<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Error\UserError;
use App\Domain\Exception\DomainException;
use App\Domain\Input\AddOAuthProviderInput;
use App\Domain\Output\AddOAuthProviderOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use BadMethodCallException;
use function mb_strtolower;
use function method_exists;
use function sprintf;
use function ucfirst;

class AddOAuthProviderInteractor implements InteractorInterface
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
    public function execute(AddOAuthProviderInput $input, PresenterInterface $presenter): void
    {
        $user = $this->checkUser($input->currentUserId);

        if ($input->currentUserId !== $input->userId) {
            throw new DomainException(UserError::PERMISSION_DENIED);
        }

        $method = 'set'.ucfirst(mb_strtolower($input->provider)).'Id';
        if (!method_exists($user, $method)) {
            throw new BadMethodCallException(sprintf('Method \'%s\' not found', $method));
        }

        $user->{$method}($input->providerId);
        $this->userRepository->saveUser($user);

        $presenter->setOutput(new AddOAuthProviderOutput($user));
    }
}
