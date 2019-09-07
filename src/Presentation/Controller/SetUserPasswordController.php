<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\SetUserPasswordInput;
use App\Domain\Interactor\SetUserPasswordInteractor;
use App\Presentation\Presenter\SetUserPasswordPresenter;
use App\Presentation\Validation\Rules\SetUserPasswordValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Patch(
 *      path="/v1.0/set/password/{token}",
 *      operationId="set_user_password",
 *      summary="Set password",
 *      description="Sets password",
 *      tags={"Users"},
 *      security={
 *          {"Key": {}},
 *      },
 *      @OA\Parameter(
 *          name="token",
 *          in="path",
 *          required=true,
 *          description="Resetting token",
 *          @OA\Schema(type="string")
 *      ),
 *      @OA\Response(response="204", description="successful operation"),
 *      @OA\Response(response="400", description="validation errors",
 *          @OA\JsonContent(ref="#/components/schemas/queryErrors")
 *      ),
 *      @OA\Response(response="403", description="token is expired",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      )
 * )
 */
class SetUserPasswordController extends AbstractController
{
    /**
     * @Route("/set/password/{token}", methods={"PATCH"}, name="set_user_password")
     */
    public function __invoke(SetUserPasswordInteractor $interactor, SetUserPasswordPresenter $presenter): JsonResponse
    {
        if (null !== $errors = $this->validate((new SetUserPasswordValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new SetUserPasswordInput(
            $this->request->getString('token'),
            $this->request->getString('data.attributes.newPassword')
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_NO_CONTENT);
    }
}
