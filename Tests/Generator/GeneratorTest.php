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
    /** @var string */
    private $docsFolder = __DIR__.'/Fixtures/';

    /** @var string */
    private $docsFile = __DIR__.'/Fixtures/index.html';

    /** @var Environment|MockObject */
    private $twig;

    /** @var ConfigParser|MockObject */
    private $parser;

    /** @var Filesystem */
    private $filesystem;

    /** @var Generator */
    private $generator;

    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->parser = $this->createMock(ConfigParser::class);
        $this->filesystem = new Filesystem();
        $this->filesystem->dumpFile($this->docsFile, '');

        $this->generator = new Generator($this->twig, $this->parser, $this->docsFolder);
    }

    protected function tearDown(): void
    {
        unset(
            $this->twig,
            $this->parser,
            $this->generator
        );

        $this->filesystem->remove($this->docsFile);
    }

    public function testGenerate(): void
    {
        $swaggerConfig = ['open api config'];

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

        self::assertStringEqualsFile($this->docsFolder.'index.html', $docs);
    }
}
