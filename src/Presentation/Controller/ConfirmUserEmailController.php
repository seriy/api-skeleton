<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\ConfirmUserEmailInput;
use App\Domain\Interactor\ConfirmUserEmailInteractor;
use App\Presentation\Presenter\ConfirmUserEmailPresenter;
use App\Presentation\Validation\Rules\ConfirmUserEmailValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Patch(
 *      path="/v1.0/confirm/email/{token}",
 *      operationId="confirm_user_email",
 *      summary="Confirm email",
 *      description="Confirms email",
 *      tags={"Users"},
 *      security={
 *          {"Key": {}},
 *      },
 *      @OA\Parameter(
 *          name="token",
 *          in="path",
 *          required=true,
 *          description="Confirmation token",
 *          @OA\Schema(type="string")
 *      ),
 *      @OA\Response(response="204", description="successful operation"),
 *      @OA\Response(response="400", description="validation errors",
 *          @OA\JsonContent(ref="#/components/schemas/queryErrors")
 *      ),
 *      @OA\Response(response="403", description="token is expired",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      ),
 *      @OA\Response(response="409", description="email is already confirmed",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      )
 * )
 */
class ConfirmUserEmailController extends AbstractController
{
    /**
     * @Route("/confirm/email/{token}", methods={"PATCH"}, name="confirm_user_email")
     */
    public function __invoke(ConfirmUserEmailInteractor $interactor, ConfirmUserEmailPresenter $presenter): JsonResponse
    {
        if (null !== $errors = $this->validate((new ConfirmUserEmailValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new ConfirmUserEmailInput(
            $this->request->getString('token'),
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_NO_CONTENT);
    }
}
