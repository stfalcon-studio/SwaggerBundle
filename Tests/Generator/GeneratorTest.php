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

namespace StfalconStudio\SwaggerBundle\Tests\Generator;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\SwaggerBundle\Generator\Generator;
use StfalconStudio\SwaggerBundle\Config\ConfigParser;
use Symfony\Component\Templating\EngineInterface;

final class GeneratorTest extends TestCase
{
    /** @var string */
    private $docsFolder = __DIR__.'/Fixtures/';

    /** @var string */
    private $docsFile = __DIR__.'/Fixtures/index.html';

    /** @var EngineInterface|MockObject */
    private $engine;

    /** @var ConfigParser|MockObject */
    private $parser;

    /** @var Generator */
    private $generator;

    protected function setUp(): void
    {
        $this->engine = $this->createMock(EngineInterface::class);
        $this->parser = $this->createMock(ConfigParser::class);

        \file_put_contents($this->docsFile, '');

        $this->generator = new Generator($this->engine, $this->parser, $this->docsFolder);
    }

    protected function tearDown(): void
    {
        unset(
            $this->engine,
            $this->parser,
            $this->generator
        );

        \unlink($this->docsFile);
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
        $this->engine
            ->expects(self::once())
            ->method('render')
            ->with('SwaggerBundle:SwaggerUi:index.html.twig', ['swagger_data' => $swaggerConfig])
            ->willReturn($docs)
        ;

        $this->generator->generate();

        self::assertStringEqualsFile($this->docsFolder.'index.html', $docs);
    }
}
