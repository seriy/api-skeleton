<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *      path="/v1.0/login",
 *      operationId="jwt_login",
 *      summary="Login",
 *      tags={"Users"},
 *      security={
 *          {"Key": {}},
 *      },
 *      @OA\RequestBody(
 *          @OA\JsonContent(type="object", required={"username", "password"},
 *              @OA\Property(property="username", type="string"),
 *              @OA\Property(property="password", type="string")
 *          )
 *      ),
 *      @OA\Response(response="200", description="successful operation",
 *          @OA\JsonContent(type="object", required={"token", "refresh_token"},
 *              @OA\Property(property="token", type="string"),
 *              @OA\Property(property="refresh_token", type="string")
 *          )
 *      ),
 *      @OA\Response(response="400", description="validation errors",
 *          @OA\JsonContent(ref="#/components/schemas/mutationErrors")
 *      ),
 *      @OA\Response(response="401", description="bad credentials",
 *          @OA\JsonContent(ref="#/components/schemas/bodyErrors")
 *      )
 * )
 */
class LoginController extends AbstractController
{
}
