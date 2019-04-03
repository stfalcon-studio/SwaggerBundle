<?php

declare(strict_types=1);

namespace StfalconStudio\SwaggerBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Templating\EngineInterface;
use StfalconStudio\SwaggerBundle\Config\ConfigParser;

/**
 * Generator.
 */
class Generator
{
    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @var ConfigParser
     */
    private $configParser;

    /**
     * @var string
     */
    private $docsFolder;

    /**
     * @param EngineInterface $engine
     * @param ConfigParser    $configParser
     * @param string          $docsFolder
     */
    public function __construct(EngineInterface $engine, ConfigParser $configParser, string $docsFolder)
    {
        $this->engine = $engine;
        $this->configParser = $configParser;
        $this->docsFolder = $docsFolder;
    }

    /**
     * Generate static file index.html with Swagger UI
     * Path: /app/public/api/index.html
     */
    public function generate(): void
    {
        $swaggerConfig = $this->configParser->parse();

        $docs = $this->engine->render('SwaggerBundle:SwaggerUi:index.html.twig', [
            'swagger_data' => $swaggerConfig
        ]);

        $filePath = $this->docsFolder.'index.html';

        $fs = new Filesystem();
        $fs->dumpFile($filePath, $docs);
    }
}