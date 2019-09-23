<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\RemoveOAuthProviderInput;
use App\Domain\Interactor\RemoveOAuthProviderInteractor;
use App\Presentation\Presenter\RemoveOAuthProviderPresenter;
use App\Presentation\Validation\Rules\RemoveOAuthProviderValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Delete(
 *      path="/v1.0/users/{userId}/providers/{provider}",
 *      operationId="remove_oauth_provider",
 *      summary="Remove OAuth provider",
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
 *      @OA\Response(response="204", description="successful operation"),
 *      @OA\Response(response="400", description="validation errors",
 *          @OA\JsonContent(ref="#/components/schemas/mutationErrors")
 *      ),
 *      @OA\Response(response="403", description="permission denied",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      )
 * )
 */
class RemoveOAuthProviderController extends AbstractController
{
    /**
     * @Route("/users/{userId<\d+>}/providers/{provider}", methods={"DELETE"}, name="remove_oauth_provider")
     */
    public function __invoke(RemoveOAuthProviderInteractor $interactor, RemoveOAuthProviderPresenter $presenter): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (null !== $errors = $this->validate((new RemoveOAuthProviderValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new RemoveOAuthProviderInput(
            $this->getCurrentUserId(),
            $this->request->getInt('userId'),
            $this->request->getString('provider')
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_NO_CONTENT);
    }
}
