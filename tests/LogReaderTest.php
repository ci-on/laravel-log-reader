<?php

namespace Mohamed\LaravelLogReader\Tests;

use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

class LogReaderTest extends TestCase
{
    protected $directory;

    protected $filesystem;

    // TODO: create log files
    // TODO: create contents on log files
    // TODO: change configs
    // TODO: change variable name
    // TODO: change path
    // TODO: get expection if Folder doesn't exist
    // TODO: get expection if File doesn't exist

    public function setUp() :void
    {
        parent::setUp();

        $this->filesystem = new Filesystem();
        $this->directory = 'logs_'.strtotime(now());

        // Create Test Folder
        $this->filesystem->makeDirectory(__DIR__ . '/'. $this->directory);
    }

    /** @test */
    public function it_is_working()
    {
        $this->assertTrue(true);

        $this->filesystem->deleteDirectory(__DIR__. '/'. $this->directory);
    }
}
