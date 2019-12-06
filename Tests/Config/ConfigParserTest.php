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

namespace StfalconStudio\SwaggerBundle\Tests\Config;

use PHPUnit\Framework\TestCase;
use StfalconStudio\SwaggerBundle\Config\ConfigParser;

final class ConfigParserTest extends TestCase
{
    /** @var ConfigParser */
    private $configParser;

    protected function setUp(): void
    {
        $this->configParser = new ConfigParser(__DIR__.'/Fixtures/api/');
    }

    protected function tearDown(): void
    {
        unset(
            $this->configParser
        );
    }

    public function testParse(): void
    {
        $expectedSwaggerConfig = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'Simple API overview',
                'version' => '2.0.0',
            ],
            'paths' => [
                '/orders' => [
                    'post' => [
                        'operationId' => 'CreateOrder',
                        'summary' => 'Create Order',
                        'responses' => [
                            '201' => [
                                'description' => '201 response',
                            ],
                        ],
                    ],
                    'get' => [
                        'operationId' => 'GetOrderList',
                        'summary' => 'Get orders list',
                    ],
                ],
                '/users' => [
                    'post' => [
                        'operationId' => 'CreateUser',
                        'summary' => 'Create User',
                        'responses' => [
                            '201' => [
                                'description' => '201 response',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        self::assertSame($expectedSwaggerConfig, $this->configParser->parse());
    }
}
