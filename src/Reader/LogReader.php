<?php

namespace Mohamed\LaravelLogReader\Reader;

use Illuminate\Filesystem\Filesystem;

 class LogReader
 {
    protected $filesystem;

    public $loggers = [];

    public function __construct()
    {
        $this->filesystem = new Filesystem;
    }

    public function read()
    {
        $this->getFiles()->each(function($file) {
            $this->getFileLines($file)->each(function($line) {
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

        // config('logreader.path')
        return collect($this->filesystem->files(storage_path('logs')));
    }

    public function getFileLines($file)
    {
        // TODO:Add Expection if this path doesn't exists

        return collect(explode("\n", $this->filesystem->get($file->getPathname())));
    }
 }
