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

namespace StfalconStudio\SwaggerBundle\Tests\Generator;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\SwaggerBundle\Config\ConfigParser;
use StfalconStudio\SwaggerBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

final class GeneratorTest extends TestCase
{
    private string $docsFolder = __DIR__.'/Fixtures/';

    private string $docsFile = __DIR__.'/Fixtures/index.html';
    private string $specificationFile = __DIR__.'/Fixtures/specification.json';

    /** @var Environment|MockObject */
    private Environment|MockObject $twig;

    /** @var ConfigParser|MockObject */
    private ConfigParser|MockObject $parser;

    private Filesystem $filesystem;

    private Generator $generator;

    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->parser = $this->createMock(ConfigParser::class);
        $this->filesystem = new Filesystem();
        $this->filesystem->dumpFile($this->docsFile, '');
        $this->filesystem->dumpFile($this->specificationFile, '');

        $this->generator = new Generator($this->twig, $this->parser, $this->docsFolder);
    }

    protected function tearDown(): void
    {
        unset(
            $this->twig,
            $this->parser,
            $this->generator,
        );

        $this->filesystem->remove($this->docsFile);
        $this->filesystem->remove($this->specificationFile);
    }

    public function testGenerate(): void
    {
        $swaggerConfig = ['open api config' => 'value'];

        $this->parser
            ->expects(self::once())
            ->method('parse')
            ->willReturn($swaggerConfig)
        ;

        $docs = 'generated docs';
        $this->twig
            ->expects(self::once())
            ->method('render')
            ->with('@Swagger/SwaggerUi/index.html.twig', ['swagger_data' => $swaggerConfig])
            ->willReturn($docs)
        ;

        $this->generator->generate();

        $swaggerConfigAsJson = <<<'EOT'
{
    "open api config": "value"
}
EOT;

        self::assertStringEqualsFile($this->docsFolder.'index.html', $docs);
        self::assertStringEqualsFile($this->docsFolder.'specification.json', $swaggerConfigAsJson);
    }
}
