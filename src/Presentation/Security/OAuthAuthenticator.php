<?php

declare(strict_types=1);

namespace App\Presentation\Security;

use App\Domain\Entity\UserInterface as User;
use App\Domain\Factory\UserFactory;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\PasswordGenerator;
use App\Presentation\Error\AuthenticationError;
use App\Presentation\Exception\PresentationException;
use App\Presentation\Service\RequestParser;
use BadMethodCallException;
use Exception;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use function in_array;
use function sprintf;

class OAuthAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $userRepository;
    private $userFactory;
    private $requestParser;
    private $successHandler;

    public function __construct(
        ClientRegistry $clientRegistry,
        UserRepositoryInterface $userRepository,
        UserFactory $userFactory,
        RequestParser $requestParser,
        AuthenticationSuccessHandler $successHandler
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->requestParser = $requestParser;
        $this->successHandler = $successHandler;
    }

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'oauth_login';
    }

    /**
     * @throws \App\Presentation\Exception\PresentationException
     */
    public function getCredentials(Request $request)
    {
        if (!in_array($provider = $this->requestParser->getString('provider'), User::SUPPORTED_OAUTH_PROVIDERS, true)) {
            throw new PresentationException(AuthenticationError::PROVIDER_NOT_SUPPORTED, [$provider]);
        }

        if (!$code = $this->requestParser->getString('code')) {
            throw new PresentationException(AuthenticationError::AUTHORIZATION_CODE_NOT_FOUND);
        }

        $client = $this->clientRegistry->getClient($provider);

        try {
            return $client->getOAuth2Provider()->getAccessToken('authorization_code', ['code' => $code]);
        } catch (Exception $exception) {
            throw new PresentationException(AuthenticationError::BAD_CREDENTIALS, [], $exception);
        }
    }

    /**
     * @throws \App\Presentation\Exception\PresentationException
     * @throws \Exception
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        if (!in_array($provider = $this->requestParser->getString('provider'), User::SUPPORTED_OAUTH_PROVIDERS, true)) {
            throw new PresentationException(AuthenticationError::PROVIDER_NOT_SUPPORTED, [$provider]);
        }

        $account = $this->clientRegistry->getClient($provider)->fetchUserFromToken($credentials);

        if (null !== $user = $this->getUserByProviderId($account->getId())) {
            return $user;
        }

        if (null !== $user = $this->userRepository->getUserByEmail($account->getEmail())) {
            $this->setProviderId($user, $account->getId());
            $user->setEmailConfirmed(true);
            $this->userRepository->saveUser($user);

            return $user;
        }

        $user = $this->userFactory->create(
            $account->getEmail(),
            (new PasswordGenerator())->generatePassword(),
            null,
            $account->getFirstName(),
            $account->getLastName(),
            $account->getAvatar() // todo: save file locally
        );

        $this->setProviderId($user, $account->getId());
        $user->setEmailConfirmed(true);
        $this->userRepository->saveUser($user);

        // todo: send email with password

        return $user;
    }

    private function setProviderId(User $user, string $id): void
    {
        $provider = $this->requestParser->getString('provider');

        $method = 'set'.ucfirst(mb_strtolower($provider)).'Id';
        if (!method_exists($user, $method)) {
            throw new BadMethodCallException(sprintf('Method \'%s\' not found', $method));
        }

        $user->{$method}($id);
    }

    private function getUserByProviderId(string $id): ?User
    {
        $provider = $this->requestParser->getString('provider');

        $method = 'getUserBy'.ucfirst(mb_strtolower($provider)).'Id';
        if (!method_exists($this->userRepository, $method)) {
            throw new BadMethodCallException(sprintf('Method \'%s\' not found', $method));
        }

        return $this->userRepository->{$method}($id);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return $this->successHandler->handleAuthenticationSuccess($token->getUser());
    }

    /**
     * @throws \App\Presentation\Exception\PresentationException
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw new PresentationException(AuthenticationError::BAD_CREDENTIALS, [], $exception);
    }

    /**
     * @throws \App\Presentation\Exception\PresentationException
     */
    public function start(Request $request, AuthenticationException $exception = null): Response
    {
        throw new PresentationException(AuthenticationError::AUTHORIZATION_CODE_NOT_FOUND, [], $exception);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
