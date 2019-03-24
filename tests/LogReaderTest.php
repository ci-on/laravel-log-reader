<?php

namespace Cion\LaravelLogReader\Tests;

use Cion\LaravelLogReader\Reader\Exception\FolderNotFoundException;
use Cion\LaravelLogReader\Reader\LogReader;
use Illuminate\Support\Facades\Storage;

class LogReaderTest extends TestCase
{
    protected $directory;

    protected $exampleLogLine;

    protected $logReader;

    public function setUp() : void
    {
        parent::setUp();

        $this->exampleLogLine = "[2019-03-10 20:40:52] local.ERROR: An informational message.";

        $this->logReader = new LogReader();
    }

    public function createLogFolder($callback)
    {
        $this->directory = __DIR__.'/logs_'.strtotime(now());
        $this->app['config']->set('logreader.path', $this->directory);

        // Create Test Folder
        $this->filesystem->makeDirectory($this->directory);

        $callback();

        $this->filesystem->deleteDirectory($this->directory);
    }

    /** @test */
    public function it_can_get_all_log_files()
    {
        $this->createLogFolder(function () {
            foreach (range(0, 4) as $loop) {
                $this->filesystem->put("{$this->directory}/laravel-".now()->addDays($loop)->format('Y-m-d').".log", $this->exampleLogLine);
            }

            $this->assertCount(5, $this->logReader->all()->get());
        });
    }

    /** @test */
    public function it_can_get_today_log_files()
    {
        $this->createLogFolder(function () {
            $this->filesystem->put("{$this->directory}/laravel-".now()->format('Y-m-d').".log", $this->exampleLogLine);
            $this->assertCount(1, $this->logReader->get());
        });
    }

    /** @test */
    public function it_can_get_yesterday_log_files()
    {
        $this->createLogFolder(function () {
            $this->filesystem->put("{$this->directory}/laravel-".now()->yesterday()->format('Y-m-d').".log", $this->exampleLogLine);

            $this->assertCount(1, $this->logReader->yesterday()->get());
        });
    }

    /** @test */
    public function it_can_get_log_files_inside_folders_on_logFolder()
    {
        $this->createLogFolder(function () {
            $this->filesystem->makeDirectory("{$this->directory}/loggers_1");

            foreach (range(0, 4) as $loop) {
                $this->filesystem->put("{$this->directory}/loggers_1/laravel-".now()->addDays($loop)->format('Y-m-d').".log", $this->exampleLogLine);
            }

            $this->filesystem->makeDirectory("{$this->directory}/loggers_2");

            foreach (range(0, 4) as $loop) {
                $this->filesystem->put("{$this->directory}/loggers_2/laravel-".now()->addDays($loop)->format('Y-m-d').".log", $this->exampleLogLine);
            }

            $this->assertCount(10, $this->logReader->all()->get());
        });
    }

    /** @test */
    public function it_throw_an_error_if_file_doesnt_exists()
    {
        $this->expectException(FolderNotFoundException::class);

        $this->app['config']->set('logreader.path', "fakeDirectory");

        $this->assertCount(1, $this->logReader->all()->get());
    }
}
