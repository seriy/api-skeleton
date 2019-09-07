<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\UnblockUserInput;
use App\Domain\Interactor\UnblockUserInteractor;
use App\Presentation\Presenter\UnblockUserPresenter;
use App\Presentation\Validation\Rules\UnblockUserValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Patch(
 *      path="/v1.0/users/{userId}/unblock",
 *      operationId="unblock_user",
 *      summary="Unblock user",
 *      description="Unblocks user account",
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
 *      @OA\Response(response="404", description="user not found",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      )
 * )
 */
class UnblockUserController extends AbstractController
{
    /**
     * @Route("/users/{userId<\d+>}/unblock", methods={"PATCH"}, name="unblock_user")
     */
    public function __invoke(UnblockUserInteractor $interactor, UnblockUserPresenter $presenter): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (null !== $errors = $this->validate((new UnblockUserValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new UnblockUserInput(
            $this->getCurrentUserId(),
            $this->request->getInt('userId')
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_OK);
    }
}
