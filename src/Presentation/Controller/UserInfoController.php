<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\UserInfoInput;
use App\Domain\Interactor\UserInfoInteractor;
use App\Presentation\Presenter\UserInfoPresenter;
use App\Presentation\Validation\Rules\UserInfoValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Get(
 *      path="/v1.0/users/{userId}",
 *      operationId="user_info",
 *      summary="User info",
 *      description="Returns user info",
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
 *          @OA\JsonContent(ref="#/components/schemas/queryErrors")
 *      ),
 *      @OA\Response(response="404", description="user not found",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      )
 * )
 */
class UserInfoController extends AbstractController
{
    /**
     * @Route("/users/{userId<\d+>}", methods={"GET"}, name="user_info")
     */
    public function __invoke(UserInfoInteractor $interactor, UserInfoPresenter $presenter): JsonResponse
    {
        if (null !== $errors = $this->validate((new UserInfoValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new UserInfoInput(
            $this->getCurrentUserId(),
            $this->request->getInt('userId')
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_OK);
    }
}
