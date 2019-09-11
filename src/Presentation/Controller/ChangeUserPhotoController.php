<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\ChangeUserPhotoInput;
use App\Domain\Interactor\ChangeUserPhotoInteractor;
use App\Presentation\Presenter\ChangeUserPhotoPresenter;
use App\Presentation\Validation\Rules\ChangeUserPhotoValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Patch(
 *      path="/v1.0/users/{userId}/photo",
 *      operationId="change_user_photo",
 *      summary="Change photo",
 *      description="Changes photo",
 *      tags={"Users"},
 *      security={
 *          {"JWT": {}},
 *      },
 *      @OA\RequestBody(
 *          @OA\JsonContent(required={"type", "id", "attributes"},
 *              @OA\Property(property="type", type="string", example="users"),
 *              @OA\Property(property="id", type="string", example="1"),
 *              @OA\Property(property="attributes", required={"photo"},
 *                  @OA\Property(property="photo", type="string", example="files/1.jpg")
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
class ChangeUserPhotoController extends AbstractController
{
    /**
     * @Route("/users/{userId<\d+>}/photo", methods={"PATCH"}, name="change_user_photo")
     */
    public function __invoke(ChangeUserPhotoInteractor $interactor, ChangeUserPhotoPresenter $presenter): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (null !== $errors = $this->validate((new ChangeUserPhotoValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new ChangeUserPhotoInput(
            $this->getCurrentUserId(),
            $this->request->getInt('userId'),
            $this->request->getString('data.attributes.photo')
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_OK);
    }
}
