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

namespace StfalconStudio\SwaggerBundle\Config;

use StfalconStudio\SwaggerBundle\Exception\UnexpectedValueException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * ConfigParser.
 */
class ConfigParser
{
    private string $configFolder;

    /**
     * @param string $configFolder
     */
    public function __construct(string $configFolder)
    {
        $this->configFolder = $configFolder;
    }

    /**
     * @return mixed[]
     */
    public function parse(): array
    {
        $config = Yaml::parseFile($this->configFolder.'index.yaml');
        if (!\is_array($config)) {
            throw new UnexpectedValueException();
        }

        return $this->iterate($config);
    }

    /**
     * @param mixed[] $config
     *
     * @throws UnexpectedValueException
     *
     * @return mixed[]
     */
    private function iterate(array $config): array
    {
        foreach ($config as $key => $value) {
            if (\is_string($value) && 0 === \strpos($value, '$')) {
                $nestedPath = \substr($value, 1);
                $path = $this->configFolder.$nestedPath;

                if (\is_dir($path)) {
                    $config[$key] = $this->parseDir($path);
                } elseif (\is_file($path)) {
                    $config[$key] = $this->parseFile($path);
                } else {
                    throw new \InvalidArgumentException(\sprintf('`%s` not exists', $path));
                }

                if (\is_array($config[$key])) {
                    $config[$key] = $this->iterate($config[$key]);
                }
            }

            if (\is_array($value)) {
                $config[$key] = $this->iterate($value);
            }
        }

        return $config;
    }

    /**
     * @param string $dirPath
     *
     * @throws UnexpectedValueException
     *
     * @return mixed[]
     */
    private function parseDir(string $dirPath): array
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in($dirPath)
            ->sortByName()
            ->name('*.yaml')
        ;

        $nestedDirs = [];
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $replacements = Yaml::parseFile($file->getPathname());
                if (!\is_array($replacements)) {
                    throw new UnexpectedValueException();
                }

                $nestedDirs = \array_replace_recursive($nestedDirs, $replacements);

                if (!\is_array($nestedDirs)) {
                    throw new \UnexpectedValueException('Expected array after parsing, NULL given');
                }
            }
        }

        return $nestedDirs;
    }

    /**
     * @param string $filePath
     *
     * @throws UnexpectedValueException
     *
     * @return mixed[]
     */
    private function parseFile(string $filePath): array
    {
        $result = Yaml::parseFile($filePath);

        if (!\is_array($result)) {
            throw new UnexpectedValueException();
        }

        return $result;
    }
}
