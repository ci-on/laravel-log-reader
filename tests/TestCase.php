<?php

namespace Cion\LaravelLogReader\Tests;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase as Orchestra;
use Cion\LaravelLogReader\ServiceProvider as LaravelLogerServiceProvider;

class TestCase extends Orchestra
{
    protected $filesystem;

    public function setUp() :void
    {
        parent::setUp();

        $this->filesystem = new Filesystem();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelLogerServiceProvider::class,
        ];
    }
}
