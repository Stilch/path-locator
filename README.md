# PathLocator

Generates an absolute path to a file or directory.

## Installation

```sh
composer require stilch/path-locator
```

Requires PHP 7.0 or newer.

## Usage

```php
<?php

use PathLocator\Exception\PathNotFoundException;
use PathLocator\PathLocator;

require __DIR__ . '/vendor/autoload.php';

//Initialize with the root directory of the project
$pathLocator = new PathLocator(__DIR__);

//Add storage directory and return full path to storage directory
$pathLocator->addPath('storageDir', 'storage');

//Returns the full path to the file image.png without checking for file existence
$pathLocator->locate('storageDir', 'image.png', false);

try {
    //Returns the full path to the file image.png or exception if file not exists
    $pathLocator->locate('storageDir', 'image.png');
} catch (PathNotFoundException $e) {
    //Exception handling
}

//Create a temporary directory and add to pathLocator
$tempDir = $pathLocator->locate('storageDir', 'temp', false);
if (!is_dir($tempDir)) {
    mkdir($tempDir, 0555);
}
$pathLocator->addPath('tempDir', $tempDir, true);

//Add a temporary system directory and return the full path to it
$pathLocator->addPath('systemTemp', sys_get_temp_dir(), true);

//Returns the path to a temporary file in the system temporary directory
$pathLocator->locate('systemTemp', 'someTmpFile.tmp', false);

//Returns the path to a temporary file in the temporary directory of the project
$pathLocator->locate('tempDir', 'someTmpFile.tmp', false);
```