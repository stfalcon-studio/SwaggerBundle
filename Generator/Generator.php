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
    /**
     * @param Environment  $twig
     * @param ConfigParser $configParser
     * @param string       $docsFolder
     */
    public function __construct(private readonly Environment $twig, private readonly ConfigParser $configParser, private readonly string $docsFolder)
    {
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
        $filePathSpecification = $this->docsFolder.'specification.json';

        $fs = new Filesystem();
        $fs->dumpFile($filePath, $docs);
        $fs->dumpFile($filePathSpecification, json_encode($swaggerConfig, \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES | \JSON_PRETTY_PRINT));
    }
}
