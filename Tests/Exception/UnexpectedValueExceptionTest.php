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

namespace StfalconStudio\SwaggerBundle\Tests\Exception;

use PHPUnit\Framework\TestCase;
use StfalconStudio\SwaggerBundle\Exception\UnexpectedValueException;

/**
 * UnexpectedValueExceptionTest.
 */
final class UnexpectedValueExceptionTest extends TestCase
{
    public function testConstructor(): void
    {
        $exception = new UnexpectedValueException();

        self::assertInstanceOf(\UnexpectedValueException::class, $exception);
    }
}
