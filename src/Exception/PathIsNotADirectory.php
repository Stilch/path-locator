<?php

declare(strict_types=1);

namespace PathLocator\Exception;

use Exception;
use Throwable;

class PathIsNotADirectory extends Exception
{
    public function __construct($path = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Path {$path} is not a directory", $code, $previous);
    }
}