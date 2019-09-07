<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\RecoverUserInput;
use App\Domain\Interactor\RecoverUserInteractor;
use App\Presentation\Presenter\RecoverUserPresenter;
use App\Presentation\Validation\Rules\RecoverUserValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Patch(
 *      path="/v1.0/users/{userId}/recover",
 *      operationId="recover_user",
 *      summary="Recover user",
 *      description="Marks user account as active",
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
 *      @OA\Response(response="409", description="user is active",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      )
 * )
 */
class RecoverUserController extends AbstractController
{
    /**
     * @Route("/users/{userId<\d+>}/recover", methods={"PATCH"}, name="recover_user")
     */
    public function __invoke(RecoverUserInteractor $interactor, RecoverUserPresenter $presenter): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (null !== $errors = $this->validate((new RecoverUserValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new RecoverUserInput(
            $this->getCurrentUserId(),
            $this->request->getInt('userId')
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_OK);
    }
}
