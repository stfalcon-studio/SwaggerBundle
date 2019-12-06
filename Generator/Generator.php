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

namespace StfalconStudio\SwaggerBundle\Generator;

use StfalconStudio\SwaggerBundle\Config\ConfigParser;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

/**
 * Generator.
 */
class Generator
{
    /** @var Environment */
    private $twig;

    /** @var ConfigParser */
    private $configParser;

    /** @var string */
    private $docsFolder;

    /**
     * @param Environment  $twig
     * @param ConfigParser $configParser
     * @param string       $docsFolder
     */
    public function __construct(Environment $twig, ConfigParser $configParser, string $docsFolder)
    {
        $this->twig = $twig;
        $this->configParser = $configParser;
        $this->docsFolder = $docsFolder;
    }

    /**
     * Generate static file index.html with Swagger UI.
     */
    public function generate(): void
    {
        $swaggerConfig = $this->configParser->parse();

        $docs = $this->twig->render(
            '@Swagger/SwaggerUi/index.html.twig',
            [
                'swagger_data' => $swaggerConfig,
            ]
        );

        $filePath = $this->docsFolder.'index.html';

        $fs = new Filesystem();
        $fs->dumpFile($filePath, $docs);
    }
}
