<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\ResetUserPasswordInput;
use App\Domain\Interactor\ResetUserPasswordInteractor;
use App\Presentation\Presenter\ResetUserPasswordPresenter;
use App\Presentation\Validation\Rules\ResetUserPasswordValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Post(
 *      path="/v1.0/reset/password",
 *      operationId="reset_user_password",
 *      summary="Reset password",
 *      description="Sends to email resetting token",
 *      tags={"Users"},
 *      security={
 *          {"Key": {}},
 *      },
 *      @OA\RequestBody(
 *          @OA\JsonContent(required={"type", "attributes"},
 *              @OA\Property(property="type", type="string", example="users"),
 *              @OA\Property(property="attributes", required={"email"},
 *                  @OA\Property(property="email", type="string", example="email@domain.com")
 *              )
 *          )
 *      ),
 *      @OA\Response(response="204", description="successful operation"),
 *      @OA\Response(response="400", description="validation errors",
 *          @OA\JsonContent(ref="#/components/schemas/queryErrors")
 *      ),
 *      @OA\Response(response="404", description="email not found",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      )
 * )
 */
class ResetUserPasswordController extends AbstractController
{
    /**
     * @Route("/reset/password", methods={"POST"}, name="reset_user_password")
     */
    public function __invoke(ResetUserPasswordInteractor $interactor, ResetUserPasswordPresenter $presenter): JsonResponse
    {
        if (null !== $errors = $this->validate((new ResetUserPasswordValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new ResetUserPasswordInput(
            $this->request->getString('data.attributes.email'),
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_NO_CONTENT);
    }
}
