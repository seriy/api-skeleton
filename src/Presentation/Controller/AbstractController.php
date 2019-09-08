<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Entity\UserInterface;
use App\Presentation\Service\RequestParser;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\Validator\Validation;

/**
 * @OA\Info(title="API Skeleton", version="1.0.0")
 *
 *
 *
 * @OA\SecurityScheme(
 *      type="http",
 *      scheme="bearer",
 *      securityScheme="JWT"
 * )
 * @OA\SecurityScheme(
 *      name="X-Api-Key",
 *      in="header",
 *      type="apiKey",
 *      scheme="Key",
 *      securityScheme="Key"
 * )
 *
 *
 *
 * @OA\Schema(type="object", schema="single", required={"data"},
 *      @OA\Property(property="data", ref="#/components/schemas/item"),
 *      @OA\Property(property="included", type="array", @OA\Items(ref="#/components/schemas/item"))
 * )
 * @OA\Schema(type="object", schema="collection", required={"meta", "data"},
 *      @OA\Property(property="meta", required={"total"}, @OA\Property(property="total", type="integer", format="int32")),
 *      @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/item")),
 *      @OA\Property(property="included", type="array", @OA\Items(ref="#/components/schemas/item"))
 * )
 * @OA\Schema(type="object", schema="item", required={"type", "id"},
 *      @OA\Property(property="type", type="string"),
 *      @OA\Property(property="id", type="string"),
 *      @OA\Property(property="attributes", type="object"),
 *      @OA\Property(property="relationship", type="object")
 * )
 *
 *
 *
 * @OA\Schema(type="object", schema="mutationErrors", required={"errors"},
 *      @OA\Property(property="errors", type="array", @OA\Items(ref="#/components/schemas/mutationError"))
 * )
 * @OA\Schema(type="object", schema="mutationError", required={"detail"},
 *      @OA\Property(property="source", @OA\Property(property="pointer", type="string")),
 *      @OA\Property(property="detail", type="string")
 * )
 *
 *
 *
 * @OA\Schema(type="object", schema="queryErrors", required={"errors"},
 *      @OA\Property(property="errors", type="array", @OA\Items(ref="#/components/schemas/queryError"))
 * )
 * @OA\Schema(type="object", schema="queryError", required={"detail"},
 *      @OA\Property(property="source", @OA\Property(property="parameter", type="string")),
 *      @OA\Property(property="detail", type="string")
 * )
 *
 *
 *
 * @OA\Schema(type="object", schema="bodyErrors", required={"errors"},
 *      @OA\Property(property="errors", type="array", @OA\Items(ref="#/components/schemas/bodyError"))
 * )
 * @OA\Schema(type="object", schema="bodyError", required={"code", "status", "detail"},
 *      @OA\Property(property="code", type="integer", format="int32"),
 *      @OA\Property(property="status", type="integer", format="int32"),
 *      @OA\Property(property="detail", type="string")
 * )
 */
class AbstractController extends BaseController
{
    protected $request;
    private $validator;

    public function __construct(RequestParser $request)
    {
        $this->request = $request;
        $this->validator = Validation::createValidator();
    }

    protected function getCurrentUserId(): ?int
    {
        $user = $this->getUser();

        return ($user instanceof UserInterface) ? $user->getId() : null;
    }

    protected function validate($constraints = null): ?array
    {
        $errors = $this->validator->validate($this->request->getData(), $constraints);

        if (0 === $errors->count()) {
            return null;
        }

        return ['errors' => $errors];
    }
}
