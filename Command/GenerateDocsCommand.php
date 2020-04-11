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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * GenerateDocsCommand.
 */
class GenerateDocsCommand extends Command
{
    protected static $defaultName = 'swagger:generate-docs';

    /** @var Generator */
    private $generator;

    /**
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        parent::__construct();

        $this->generator = $generator;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Generate swagger docs')
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
