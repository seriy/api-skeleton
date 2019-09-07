<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\AddUserInput;
use App\Domain\Interactor\AddUserInteractor;
use App\Presentation\Presenter\AddUserPresenter;
use App\Presentation\Validation\Rules\AddUserValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Post(
 *      path="/v1.0/register",
 *      operationId="add_user",
 *      summary="Registration",
 *      description="Registers user account",
 *      tags={"Users"},
 *      security={
 *          {"Key": {}},
 *      },
 *      @OA\Response(response="201", description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/single")
 *      ),
 *      @OA\Response(response="400", description="validation errors",
 *          @OA\JsonContent(ref="#/components/schemas/mutationErrors")
 *      ),
 *      @OA\Response(response="409", description="email or username is already taken",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      )
 * )
 */
class AddUserController extends AbstractController
{
    /**
     * @Route("/register", methods={"POST"}, name="add_user")
     */
    public function __invoke(AddUserInteractor $interactor, AddUserPresenter $presenter): JsonResponse
    {
        if (null !== $errors = $this->validate((new AddUserValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new AddUserInput(
            $this->request->getString('data.attributes.email'),
            $this->request->getString('data.attributes.username'),
            $this->request->getString('data.attributes.password')
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_CREATED);
    }
}
