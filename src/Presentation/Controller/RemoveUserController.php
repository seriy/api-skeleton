<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\RemoveUserInput;
use App\Domain\Interactor\RemoveUserInteractor;
use App\Presentation\Presenter\RemoveUserPresenter;
use App\Presentation\Validation\Rules\RemoveUserValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Delete(
 *      path="/v1.0/users/{userId}",
 *      operationId="remove_user",
 *      summary="Remove user",
 *      description="Marks user account as deleted",
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
 *      @OA\Response(response="204", description="successful operation"),
 *      @OA\Response(response="403", description="permission denied",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      )
 * )
 */
class RemoveUserController extends AbstractController
{
    /**
     * @Route("/users/{userId<\d+>}", methods={"DELETE"}, name="remove_user")
     */
    public function __invoke(RemoveUserInteractor $interactor, RemoveUserPresenter $presenter): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (null !== $errors = $this->validate((new RemoveUserValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new RemoveUserInput(
            $this->getCurrentUserId(),
            $this->request->getInt('userId')
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_NO_CONTENT);
    }
}
