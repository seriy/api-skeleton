<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\BlockUserInput;
use App\Domain\Interactor\BlockUserInteractor;
use App\Presentation\Presenter\BlockUserPresenter;
use App\Presentation\Validation\Rules\BlockUserValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Patch(
 *      path="/v1.0/users/{userId}/block",
 *      operationId="block_user",
 *      summary="Block user",
 *      description="Blocks user account",
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
class BlockUserController extends AbstractController
{
    /**
     * @Route("/users/{userId<\d+>}/block", methods={"PATCH"}, name="block_user")
     */
    public function __invoke(BlockUserInteractor $interactor, BlockUserPresenter $presenter): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (null !== $errors = $this->validate((new BlockUserValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new BlockUserInput(
            $this->getCurrentUserId(),
            $this->request->getInt('userId'),
            $this->request->getDate('data.attributes.blockedTo'),
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_OK);
    }
}
