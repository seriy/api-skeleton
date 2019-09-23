<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *      path="/v1.0/connect/{provider}",
 *      operationId="oauth_login",
 *      summary="OAuth login",
 *      tags={"Users"},
 *      security={
 *          {"Key": {}},
 *      },
 *      @OA\Parameter(
 *          name="provider",
 *          in="path",
 *          required=true,
 *          description="OAuth provider",
 *          @OA\Schema(type="string", example="google")
 *      ),
 *      @OA\RequestBody(
 *          @OA\JsonContent(required={"type", "attributes"},
 *              @OA\Property(property="type", type="string", example="users"),
 *              @OA\Property(property="attributes", required={"code"},
 *                  @OA\Property(property="code", type="string", example="4/rQEr5DHX11CFp8VruEMecYazHJOZRe_Ea8vIVCMqkGKKaDK2oChpU9RD2830oJm_lEc_Te8jrFJiBW5ga5ygOGU")
 *              )
 *          )
 *      ),
 *      @OA\Response(response="200", description="successful operation",
 *          @OA\JsonContent(required={"token", "refresh_token"},
 *              @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1NjYzODg5NzksImV4cCI6MTU2NjM5MjU3OSwicm9sZXMiOlsiUk9MRV9VU0VSIiwiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6InVzZXJuYW1lIn0.IsvrAL-GdZMcxuO3MiT7lPMSUCVlOLHVVreIMO8ZeY2BMLlPUu-NQA-q7ujEkb6bNMPkmWPM-hxBrkfKuHXhd0ARIx0rX3kr9MZA3QOUpjJEbgHahaHRAR9rZn6bMCORszPnIqmOEtcTz6Ng1N0BfAuEj4XlNTUAe-LvQm_lSWUim3xiCgDtgCyRvSif8SkPD4In6i-0eL_1uQxAezMbEVq6gAosCUde1YayJx3BLIsEUbfOfH-J0wlIssKSw_sPocq5FLAhdKAAs6c_QtBgdPn-sm4Ds-hPVpD-kA7kzB-niA2jL6eR51o9TNWCGc-o1WNahuwMr4_b4FPzY5dFWyNyznwl1-tnf5g1j3ZGTmZU2C9xP9uFlsR1z0lyTtQct5kjqncCZn_OGm9F3F6vTiCDFOqAMTbZer_XoDGwOJ59j3_GXQcGGoMp9zHnOF80_888Dk6ihElPmIW-w3BEGeSoTEu1xS8UjNFFjDr60iuv6CGJef402GGeRPT9ksjTBr_7L_3r30G9PHN3y-rJu0o_cxFLczoKf5InhkP6yx9myeeiDlOrCkLJzryezIf1vl1t8hlK6jQ8Gtmm4kJM_RxvvOLyANi4_D5ntSRyhSZ8a1UbTS3oauQUaJs4ksk_QSxs7WvoaZ-sJ1MHsmXQgcnnkFKrcL-i4M1ZPAUjv2Q"),
 *              @OA\Property(property="refresh_token", type="string", example="8a6c881716b20965b87cbb13de42a9183f5282a26dbf9624f1f1dc6a25035197010bf53ad3f042fb1e65567f313bbc595b9d72c754916a2eb446d2868a2f2b60")
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
class OAuthLoginController extends AbstractController
{
}
