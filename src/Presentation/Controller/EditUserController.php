<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\EditUserInput;
use App\Domain\Interactor\EditUserInteractor;
use App\Presentation\Presenter\EditUserPresenter;
use App\Presentation\Validation\Rules\EditUserValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Patch(
 *      path="/v1.0/users/{userId}",
 *      operationId="edit_user",
 *      summary="Edit user",
 *      description="Edits user account",
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
 *      @OA\Response(response="409", description="email or username is already taken",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      )
 * )
 */
class EditUserController extends AbstractController
{
    /**
     * @Route("/users/{userId<\d+>}", methods={"PATCH"}, name="edit_user")
     */
    public function __invoke(EditUserInteractor $interactor, EditUserPresenter $presenter): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (null !== $errors = $this->validate((new EditUserValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new EditUserInput(
            $this->getCurrentUserId(),
            $this->request->getInt('userId'),
            $this->request->getString('data.attributes.email'),
            $this->request->getString('data.attributes.username'),
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_OK);
    }
}
