<?php

namespace Mohamed\LaravelLogReader\Reader;

use Illuminate\Filesystem\Filesystem;

class LogReader
{
    protected $filesystem;

    protected $time;

    public $loggers = [];

    public function __construct()
    {
        $this->filesystem = new Filesystem;

        $this->time = now()->format('Y-m-d');
    }

    public function read()
    {
        $this->getFiles()->each(function ($file) {
            $this->getFileLines($file)->each(function ($line) {
                if ($lineHandler = (new LineReader($line))->handle()) {
                    $this->loggers[] = $lineHandler->getArray();
                }
            });
        });

        return $this;
    }

    public function toArray()
    {
        return $this->loggers;
    }

    public function toDatabase()
    {
        // specify table or model
    }

    public function getFiles()
    {
        // TODO:Add Expection if this path doesn't exists

        return collect($this->filesystem->files(config('logreader.path')))->filter(function ($file) {
            if (! is_null($this->time)) {
                return $file->getFilename() === "laravel-{$this->time}.log";
            }

            return true;
        });
    }

    public function getFileLines($file)
    {
        // TODO:Add Expection if this path doesn't exists

        return collect(explode("\n", $this->filesystem->get($file->getPathname())));
    }

    public function all()
    {
        $this->time = null;

        return $this;
    }


    public function yesterday()
    {
        $this->time = now()->subDay()->format('Y-m-d');

        return $this;
    }
}
