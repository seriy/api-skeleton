<?php

declare(strict_types=1);

namespace App\Presentation\Command;

use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteUsersCommand extends Command
{
    protected static $defaultName = 'app:user:delete';
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        parent::__construct(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->userRepository->deleteUsers();

        $output->writeln('Users have been deleted');
    }
}
