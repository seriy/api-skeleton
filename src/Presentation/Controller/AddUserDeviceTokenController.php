<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\AddUserDeviceTokenInput;
use App\Domain\Interactor\AddUserDeviceTokenInteractor;
use App\Presentation\Presenter\AddUserDeviceTokenPresenter;
use App\Presentation\Validation\Rules\AddUserDeviceTokenValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Post(
 *      path="/v1.0/users/{userId}/device-tokens",
 *      operationId="add_user_device_token",
 *      summary="Add device token",
 *      description="Adds device token",
 *      tags={"Users"},
 *      security={
 *          {"JWT": {}},
 *      },
 *      @OA\Parameter(
 *          name="userId",
 *          in="path",
 *          required=true,
 *          description="User ID",
 *          @OA\Schema(type="integer", format="int32", example="1")
 *      ),
 *      @OA\RequestBody(
 *          @OA\JsonContent(required={"type", "id", "attributes"},
 *              @OA\Property(property="type", type="string", example="users"),
 *              @OA\Property(property="id", type="string", example="1"),
 *              @OA\Property(property="attributes", required={"deviceToken"},
 *                  @OA\Property(property="deviceToken", type="string", example="03df25c845d460bcdad7802d2vf6fc1dfde97283bf75cc993eb6dca835ea2e2f")
 *              )
 *          )
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
class AddUserDeviceTokenController extends AbstractController
{
    /**
     * @Route("/users/{userId<\d+>}/device-tokens", methods={"POST"}, name="add_user_device_token")
     */
    public function __invoke(AddUserDeviceTokenInteractor $interactor, AddUserDeviceTokenPresenter $presenter): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (null !== $errors = $this->validate((new AddUserDeviceTokenValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new AddUserDeviceTokenInput(
            $this->getCurrentUserId(),
            $this->request->getInt('userId'),
            $this->request->getString('data.attributes.deviceToken'),
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_OK);
    }
}
