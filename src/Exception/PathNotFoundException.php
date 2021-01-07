<?php

declare(strict_types=1);

namespace PathLocator\Exception;

use Exception;
use Throwable;

class PathNotFoundException extends Exception
{
    public function __construct($path = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Path {$path} not found", $code, $previous);
    }
}