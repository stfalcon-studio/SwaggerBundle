<?php

declare(strict_types=1);

namespace StfalconStudio\SwaggerBundle\Config;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * ConfigParser.
 */
class ConfigParser
{
    /**
     * @var string
     */
    private $configFolder;

    /**
     * Iterator constructor.
     *
     * @param string $configFolder
     */
    public function __construct(string $configFolder)
    {
        $this->configFolder = $configFolder;
    }

    /**
     * @return array
     */
    public function parse(): array
    {
        $config = Yaml::parseFile($this->configFolder.'index.yaml');
        $config = $this->iterate($config);

        return $config;
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function iterate(array $config): array
    {
        foreach ($config as $key => $value) {
            if (\is_string($value) && 0 === \strpos($value, '$')) {
                $nestedPath = \substr($value, 1);
                $path = $this->configFolder.$nestedPath;

                if (\is_dir($path)) {
                    $config[$key] = $this->parseDir($path);
                } else if(\is_file($path)) {
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
     * @return array
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
                $nestedDirs = \array_replace_recursive($nestedDirs, Yaml::parseFile($file->getPathname()));
            }
        }

        return $nestedDirs;
    }

    /**
     * @param string $filePath
     *
     * @return array
     */
    private function parseFile(string $filePath): array
    {
        return Yaml::parseFile($filePath);
    }
}