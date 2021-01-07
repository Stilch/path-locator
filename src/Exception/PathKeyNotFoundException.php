<?php

declare(strict_types=1);

namespace PathLocator\Exception;

use Exception;
use Throwable;

class PathKeyNotFoundException extends Exception
{
    public function __construct($pathKey = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Directory not found for key {$pathKey}", $code, $previous);
    }
}