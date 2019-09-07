<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\VerifyUserEmailInput;
use App\Domain\Interactor\VerifyUserEmailInteractor;
use App\Presentation\Presenter\VerifyUserEmailPresenter;
use App\Presentation\Validation\Rules\VerifyUserEmailValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Post(
 *      path="/v1.0/users/{userId}/email/verify",
 *      operationId="verify_user_email",
 *      summary="Verify email",
 *      description="Sends to email confirmation token",
 *      tags={"Users"},
 *      security={
 *          {"JWT": {}},
 *      },
 *      @OA\Parameter(
 *          name="userId",
 *          in="path",
 *          required=true,
 *          description="User ID",
 *          @OA\Schema(type="integer", format="int32")
 *      ),
 *      @OA\Response(response="200", description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/single")
 *      ),
 *      @OA\Response(response="400", description="validation errors",
 *          @OA\JsonContent(ref="#/components/schemas/mutationErrors")
 *      ),
 *      @OA\Response(response="403", description="permission denied",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      ),
 *      @OA\Response(response="409", description="email is already confirmed",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      )
 * )
 */
class VerifyUserEmailController extends AbstractController
{
    /**
     * @Route("/users/{userId<\d+>}/email/verify", methods={"POST"}, name="verify_user_email")
     */
    public function __invoke(VerifyUserEmailInteractor $interactor, VerifyUserEmailPresenter $presenter): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (null !== $errors = $this->validate((new VerifyUserEmailValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new VerifyUserEmailInput(
            $this->getCurrentUserId(),
            $this->request->getInt('userId')
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_OK);
    }
}
