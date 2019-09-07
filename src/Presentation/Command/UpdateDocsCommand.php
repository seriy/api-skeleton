<?php

declare(strict_types=1);

namespace App\Presentation\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function OpenApi\scan;

class UpdateDocsCommand extends Command
{
    protected static $defaultName = 'app:docs:update';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scanner = scan(__DIR__.'/../../../src');
        $scanner->saveAs(__DIR__.'/../../../public/openapi.json');

        $output->writeln('Documentation has been updated');
    }
}
