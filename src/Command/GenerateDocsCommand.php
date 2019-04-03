<?php

declare(strict_types=1);

namespace StfalconStudio\SwaggerBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use StfalconStudio\SwaggerBundle\Generator\Generator;

/**
 * GenerateDocsCommand.
 */
class GenerateDocsCommand extends Command
{
    protected static $defaultName = 'swagger:generate-docs';

    /**
     * @var Generator
     */
    private $generator;

    /**
     * GenerateDocs constructor.
     *
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;

        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Generate swagger docs')
            ->setHelp('This command generate swagger docs.')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->generator->generate();

        $output->writeln('<fg=green>Swagger UI successfully generated and is available at `/api/index.html`</>');
    }
}