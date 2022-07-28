<?php
/*
 * This file is part of the SwaggerBundle.
 *
 * (c) Stfalcon LLC <stfalcon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace StfalconStudio\SwaggerBundle\Command;

use StfalconStudio\SwaggerBundle\Generator\Generator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * GenerateDocsCommand.
 */
#[AsCommand(name: 'swagger:generate-docs', description: 'Generates swagger docs')]
class GenerateDocsCommand extends Command
{
    /**
     * @param Generator $generator
     */
    public function __construct(private readonly Generator $generator)
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setHelp('This command generate swagger docs.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->generator->generate();

        $output->writeln('<fg=green>Swagger UI successfully generated and is available at `/api/index.html`</>');

        return 0;
    }
}
