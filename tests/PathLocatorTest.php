<?php

use PathLocator\Exception\PathIsNotADirectory;
use PathLocator\Exception\PathKeyNotFoundException;
use PathLocator\Exception\PathNotFoundException;
use PathLocator\Exception\WrongFileNameException;
use PathLocator\PathLocator;
use PHPUnit\Framework\TestCase;

class PathLocatorTest extends TestCase
{
    protected static $pathLocator;
    protected static $rootDir;

    public static function setUpBeforeClass()
    {
        self::$rootDir = realpath(__DIR__ . '/../');

        self::$pathLocator = new PathLocator(self::$rootDir);
    }

    public function testAddPath()
    {
        $this->assertEquals(
            self::$rootDir . DIRECTORY_SEPARATOR . 'tests',
            self::$pathLocator->addPath('tests', 'tests')
        );

        $this->assertEquals(
            self::$rootDir . DIRECTORY_SEPARATOR . 'tests',
            self::$pathLocator->addPath('tests', 'someDir')
        );

        $this->assertEquals(
            __DIR__,
            self::$pathLocator->addPath('current', __DIR__, true)
        );
    }

    public function testAddPathEmptyPathException()
    {
        $this->expectException(InvalidArgumentException::class);
        self::$pathLocator->addPath('tests', '');
    }

    public function testAddPathKeyEmptyPathException()
    {
        $this->expectException(InvalidArgumentException::class);
        self::$pathLocator->addPath('', 'tests');
    }

    public function testAddPathPathNotFoundException()
    {
        $this->expectException(PathNotFoundException::class);
        self::$pathLocator->addPath('notFoundDir', 'someNotFoundDir');
    }

    public function testAddPathPathIsNotADirectory()
    {
        $this->expectException(PathIsNotADirectory::class);
        self::$pathLocator->addPath('file', __FILE__, true);
    }

    public function testLocatePathKeyNotFoundException()
    {
        $this->expectException(PathKeyNotFoundException::class);
        self::$pathLocator->locate('notFoundDir');
    }

    public function testLocateWrongFileNameException()
    {
        $this->expectException(WrongFileNameException::class);
        self::$pathLocator->locate('current', '/../');
    }

    public function testLocatePathNotFoundException()
    {
        $this->expectException(PathNotFoundException::class);
        self::$pathLocator->locate('root', 'someNotFoundDir');
    }

    public function testLocate()
    {
        $this->assertEquals(
            __FILE__,
            self::$pathLocator->locate('current', pathinfo(__FILE__, PATHINFO_BASENAME))
        );

        $this->assertEquals(
            self::$rootDir . DIRECTORY_SEPARATOR . 'tests',
            self::$pathLocator->locate('root', 'tests')
        );

        $this->assertEquals(
            self::$rootDir . DIRECTORY_SEPARATOR . 'someNotFoundDir',
            self::$pathLocator->locate('root', 'someNotFoundDir', false)
        );
    }
}
