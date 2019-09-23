<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\AddOAuthProviderInput;
use App\Domain\Interactor\AddOAuthProviderInteractor;
use App\Domain\Repository\UserRepositoryInterface;
use App\Presentation\Error\AuthenticationError;
use App\Presentation\Exception\PresentationException;
use App\Presentation\Presenter\AddOAuthProviderPresenter;
use App\Presentation\Validation\Rules\AddOAuthProviderValidationRules;
use Exception;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Post(
 *      path="/v1.0/users/{userId}/providers/{provider}",
 *      operationId="add_oauth_provider",
 *      summary="Add OAuth provider",
 *      tags={"Users"},
 *      security={
 *          {"JWT": {}},
 *      },
 *      @OA\Parameter(
 *          name="userId",
 *          in="path",
 *          required=true,
 *          description="User ID",
 *          @OA\Schema(type="integer", format="int32", example="1")
 *      ),
 *      @OA\Parameter(
 *          name="provider",
 *          in="path",
 *          required=true,
 *          description="OAuth provider",
 *          @OA\Schema(type="string", example="google")
 *      ),
 *      @OA\RequestBody(
 *          @OA\JsonContent(required={"type", "id", "attributes"},
 *              @OA\Property(property="type", type="string", example="users"),
 *              @OA\Property(property="id", type="string", example="1"),
 *              @OA\Property(property="attributes", required={"code"},
 *                  @OA\Property(property="code", type="string", example="4/rQEr5DHX11CFp8VruEMecYazHJOZRe_Ea8vIVCMqkGKKaDK2oChpU9RD2830oJm_lEc_Te8jrFJiBW5ga5ygOGU")
 *              )
 *          )
 *      ),
 *      @OA\Response(response="200", description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/single")
 *      ),
 *      @OA\Response(response="400", description="validation errors",
 *          @OA\JsonContent(ref="#/components/schemas/mutationErrors")
 *      ),
 *      @OA\Response(response="403", description="permission denied",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      )
 * )
 */
class AddOAuthProviderController extends AbstractController
{
    /**
     * @Route("/users/{userId<\d+>}/providers/{provider}", methods={"POST"}, name="add_oauth_provider")
     */
    public function __invoke(ClientRegistry $clientRegistry, UserRepositoryInterface $userRepository, AddOAuthProviderInteractor $interactor, AddOAuthProviderPresenter $presenter): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (null !== $errors = $this->validate((new AddOAuthProviderValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new AddOAuthProviderInput(
            $this->getCurrentUserId(),
            $this->request->getInt('userId'),
            $this->request->getString('provider'),
            $this->getOAuthAccountId($clientRegistry)
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_OK);
    }

    private function getOAuthAccountId(ClientRegistry $clientRegistry)
    {
        try {
            $client = $clientRegistry->getClient($this->request->getString('provider'));
            $token = $client->getOAuth2Provider()->getAccessToken(
                'authorization_code',
                ['code' => $this->request->getString('data.attributes.code')]
            );
            $account = $client->fetchUserFromToken($token);

            return (string) $account->getId();
        } catch (Exception $exception) {
            throw new PresentationException(AuthenticationError::BAD_CREDENTIALS, [], $exception);
        }
    }
}
