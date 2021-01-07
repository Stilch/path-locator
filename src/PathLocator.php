<?php

declare(strict_types=1);

namespace PathLocator;

use InvalidArgumentException;
use PathLocator\Exception\PathIsNotADirectory;
use PathLocator\Exception\PathKeyNotFoundException;
use PathLocator\Exception\PathNotFoundException;
use PathLocator\Exception\WrongFileNameException;

class PathLocator implements PathLocatorInterface
{
    /**
     * @var array Array of keys and their paths
     */
    private $paths = [];

    /**
     * @param string $rootDir
     *
     * @throws PathNotFoundException If directory not found
     * @throws PathKeyNotFoundException If path key not exists
     * @throws PathIsNotADirectory If the specified path is not a directory
     */
    public function __construct(string $rootDir)
    {
        $this->addPath('root', $rootDir, true);
    }

    /**
     * Adds a key and directory
     *
     * @param string $pathKey
     * @param string $path
     * @param bool $absolutePath Absolute path or not
     *
     * @return string Added path
     *
     * @throws PathNotFoundException If directory not found
     * @throws PathKeyNotFoundException If path key not exists
     * @throws PathIsNotADirectory If the specified path is not a directory
     */
    public function addPath(string $pathKey, string $path, bool $absolutePath = false): string
    {
        if ($pathKey === '') {
            throw new InvalidArgumentException('Path key is empty');
        }

        if ($path === '') {
            throw new InvalidArgumentException('Path is empty');
        }

        if (isset($this->paths[$pathKey])) {
            return $this->paths[$pathKey];
        }

        if (!$absolutePath) {
            $path = $this->getPathByKey('root') . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
        }

        $realPath = realpath($path);

        if ($realPath === false) {
            throw new PathNotFoundException($path);
        }

        if (!is_dir($realPath)) {
            throw new PathIsNotADirectory($path);
        }

        $this->paths[$pathKey] = $realPath;

        return $realPath;
    }

    /**
     * Returns the path by key
     *
     * @param string $pathKey
     *
     * @return string Path
     *
     * @throws PathKeyNotFoundException If path key not exists
     */
    private function getPathByKey(string $pathKey): string
    {
        if (!isset($this->paths[$pathKey])) {
            throw new PathKeyNotFoundException($pathKey);
        }

        return $this->paths[$pathKey];
    }

    /**
     * @inheritDoc
     */
    public function locate(string $pathKey, string $fileOrDirName = '', $checkExistence = true): string
    {
        if (pathinfo($fileOrDirName, PATHINFO_BASENAME) !== $fileOrDirName) {
            throw new WrongFileNameException('The name of the file or directory should not contain transitions to another directory');
        }

        $path = $this->getPathByKey($pathKey) . DIRECTORY_SEPARATOR . ltrim($fileOrDirName, '/\\');

        if ($checkExistence and @file_exists($path) === false) {
            throw new PathNotFoundException($path);
        }

        return $path;
    }
}
