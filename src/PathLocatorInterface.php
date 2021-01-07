<?php

namespace PathLocator;

use PathLocator\Exception\PathKeyNotFoundException;
use PathLocator\Exception\PathNotFoundException;
use PathLocator\Exception\WrongFileNameException;

interface PathLocatorInterface
{
    /**
     * Returns the full path to a file or directory name
     *
     * @param string $pathKey
     * @param string $fileOrDirName File or directory name
     * @param bool $checkExistence Check for file or directory existence
     *
     * @return string Full path to a file or directory name
     *
     * @throws PathKeyNotFoundException If path key not exists
     * @throws PathNotFoundException If path not found
     * @throws WrongFileNameException If file or directory name is not set correctly
     */
    public function locate(string $pathKey, string $fileOrDirName = '', $checkExistence = true): string;
}