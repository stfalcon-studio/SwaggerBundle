<?php
/*
 * This file is part of the SwaggerBundle.
 *
 * (c) Stfalcon Studio <stfalcon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    private $engine;

    private $configParser;

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
            'swagger_data' => $swaggerConfig,
        ]);

        $filePath = $this->docsFolder.'index.html';

        $fs = new Filesystem();
        $fs->dumpFile($filePath, $docs);
    }
}
