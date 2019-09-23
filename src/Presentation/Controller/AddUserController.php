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
 *      @OA\RequestBody(
 *          @OA\JsonContent(required={"type", "attributes"},
 *              @OA\Property(property="type", type="string", example="users"),
 *              @OA\Property(property="attributes", required={"email", "username", "password", "firstName", "lastName"},
 *                  @OA\Property(property="email", type="string", example="email@domain.com"),
 *                  @OA\Property(property="username", type="string", example="username"),
 *                  @OA\Property(property="password", type="string", example="5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8"),
 *                  @OA\Property(property="firstName", type="string", example="John"),
 *                  @OA\Property(property="lastName", type="string", example="Doe")
 *              )
 *          )
 *      ),
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
            $this->request->getString('data.attributes.password'),
            $this->request->getString('data.attributes.firstName'),
            $this->request->getString('data.attributes.lastName')
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_CREATED);
    }
}
