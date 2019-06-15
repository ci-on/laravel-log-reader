<?php
namespace Cion\LaravelLogReader\Tests;

use Cion\LaravelLogReader\Reader\LogSectionReader;
use Cion\LaravelLogReader\Reader\LogReader;
use Illuminate\Filesystem\Filesystem;

class LogReaderSectionTest extends TestCase
{
    const PATH = __DIR__.'/log/laravel.log' ;

    protected $filesystem;

    public function setUp() : void
    {
        parent::setUp();

        $this->filesystem = new Filesystem;
    }

    /** @test */
    public function can_retrieve_log_sections()
    {
        $sections = (new LogReader)->retrieveSections($this->filesystem->get(static::PATH));

        $this->assertCount(5, $sections);
    }
}
