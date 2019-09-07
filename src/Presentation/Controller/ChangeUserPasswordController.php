<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\ChangeUserPasswordInput;
use App\Domain\Interactor\ChangeUserPasswordInteractor;
use App\Presentation\Presenter\ChangeUserPasswordPresenter;
use App\Presentation\Validation\Rules\ChangeUserPasswordValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Patch(
 *      path="/v1.0/users/{userId}/password",
 *      operationId="change_user_password",
 *      summary="Change password",
 *      description="Changes password",
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
 *      )
 * )
 */
class ChangeUserPasswordController extends AbstractController
{
    /**
     * @Route("/users/{userId<\d+>}/password", methods={"PATCH"}, name="change_user_password")
     */
    public function __invoke(ChangeUserPasswordInteractor $interactor, ChangeUserPasswordPresenter $presenter): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (null !== $errors = $this->validate((new ChangeUserPasswordValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new ChangeUserPasswordInput(
            $this->getCurrentUserId(),
            $this->request->getInt('userId'),
            $this->request->getString('data.attributes.currentPassword'),
            $this->request->getString('data.attributes.newPassword')
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_OK);
    }
}
