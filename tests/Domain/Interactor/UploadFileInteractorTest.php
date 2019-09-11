<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\File;
use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Input\UploadFileInput;
use App\Domain\Interactor\UploadFileInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class UploadFileInteractorTest extends TestCase
{
    public function testUserNotFound()
    {
        $this->expectException(DomainException::class);

        $input = new UploadFileInput(
            $currentUserId = 1,
            [new File('name.jpg', 'files/1.jpg')]
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn(null);

        $interactor = new UploadFileInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccess()
    {
        $input = new UploadFileInput(
            $currentUserId = 1,
            [new File('name.jpg', 'files/1.jpg')]
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $presenter = $this->createMock(PresenterInterface::class);
        $presenter
            ->expects($this->once())
            ->method('setOutput');

        $interactor = new UploadFileInteractor($userRepository);
        $interactor->execute($input, $presenter);
    }
}
