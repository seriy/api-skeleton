<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Input\UserListInput;
use App\Domain\Interactor\UserListInteractor;
use App\Presentation\Presenter\UserListPresenter;
use App\Presentation\Validation\Rules\UserListValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Get(
 *      path="/v1.0/users",
 *      operationId="user_list",
 *      summary="Search user",
 *      description="Returns users",
 *      tags={"Users"},
 *      security={
 *          {"JWT": {}},
 *      },
 *      @OA\Parameter(
 *          name="filter[id]",
 *          in="query",
 *          description="Filtering",
 *          @OA\Schema(type="string", example="1")
 *      ),
 *      @OA\Parameter(
 *          name="filter[username]",
 *          in="query",
 *          description="Filtering",
 *          @OA\Schema(type="string", example="user")
 *      ),
 *      @OA\Parameter(
 *          name="sort",
 *          in="query",
 *          description="Sorting",
 *          @OA\Schema(type="string", example="-id")
 *      ),
 *      @OA\Parameter(
 *          name="page[limit]",
 *          in="query",
 *          description="Limit",
 *          @OA\Schema(type="integer", format="int32", example="10")
 *      ),
 *      @OA\Parameter(
 *          name="page[offset]",
 *          in="query",
 *          description="Offset",
 *          @OA\Schema(type="integer", format="int32", example="0")
 *      ),
 *      @OA\Response(response="200", description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/collection")
 *      ),
 *      @OA\Response(response="400", description="validation errors",
 *          @OA\JsonContent(ref="#/components/schemas/queryErrors")
 *      )
 * )
 */
class UserListController extends AbstractController
{
    /**
     * @Route("/users", methods={"GET"}, name="user_list")
     */
    public function __invoke(UserListInteractor $interactor, UserListPresenter $presenter): JsonResponse
    {
        if (null !== $errors = $this->validate((new UserListValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $input = new UserListInput(
            $this->getCurrentUserId(),
            $this->request->getFilters(),
            $this->request->getSorts(),
            $this->request->getLimit(),
            $this->request->getOffset()
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_OK);
    }
}
