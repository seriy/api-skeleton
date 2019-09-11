<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Entity\File;
use App\Domain\Input\UploadFileInput;
use App\Domain\Interactor\UploadFileInteractor;
use App\Presentation\Presenter\UploadFilePresenter;
use App\Presentation\Service\FileSaver;
use App\Presentation\Validation\Rules\UploadFileValidationRules;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Post(
 *      path="/v1.0/files",
 *      operationId="upload_file",
 *      summary="Upload file",
 *      description="Uploads file",
 *      tags={"Users"},
 *      security={
 *          {"JWT": {}},
 *      },
 *      @OA\RequestBody(
 *          @OA\Schema(type="multipart/form-data", required={"file[]"},
 *              @OA\Property(property="file[]", type="file")
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
class UploadFileController extends AbstractController
{
    /**
     * @Route("/files", methods={"POST"}, name="upload_file")
     */
    public function __invoke(UploadFileInteractor $interactor, UploadFilePresenter $presenter): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (null !== $errors = $this->validate((new UploadFileValidationRules())->getRules())) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $files = [];
        $errors = [];
        $fileSaver = new FileSaver();

        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
        foreach ($this->request->getArray('file') as $file) {
            try {
                $data = $fileSaver->save($file, 'files/'.$this->getCurrentUserId());
                $files[] = new File($data['originalName'], $data['path']);
            } catch (FileException $exception) {
                $errors[] = [
                    'detail' => $exception->getMessage(),
                ];
            }
        }

        // todo: handle errors and show successful uploads

        $input = new UploadFileInput(
            $this->getCurrentUserId(),
            $files
        );

        $interactor->execute($input, $presenter);

        return $this->json($presenter->getOutput(), Response::HTTP_OK);
    }
}
