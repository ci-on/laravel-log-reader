<?php

namespace Cion\LaravelLogReader\Reader;

use Illuminate\Filesystem\Filesystem;
use Cion\LaravelLogReader\Reader\Exception\FolderNotFoundException;

class LogReader
{
    protected $filesystem;

    protected $files = [];

    protected $time;

    public $loggers = [];

    public function __construct()
    {
        $this->filesystem = new Filesystem;

        $this->time = now();
    }

    public function read()
    {
        $this->get()->each(function ($file) {
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

    public function get()
    {
        // TODO:Add Expection if this path doesn't exists
        if (! $this->filesystem->exists(config('logreader.path'))) {
            throw new FolderNotFoundException();
            // throw new Exception("File Does Not Exists");
        }

        return $this->getDiretoryFiles()->getSubDirectoriesFiles()->getFiles();
    }

    public function getFiles()
    {
        return collect($this->files)->filter(function ($file) {
            if (! is_null($this->getTime())) {
                // Get only the files that has the current time
                return $file->getFilename() === "laravel-{$this->getTime()}.log";
            }

            return true;
        });
    }

    public function getSubDirectoriesFiles()
    {
        collect($this->filesystem->directories(config('logreader.path')))->map(function ($directory) {
            collect($this->filesystem->files($directory))->filter(function ($file) {
                $this->files[] = $file;
            });
        });

        return $this;
    }

    public function getDiretoryFiles()
    {
        collect($this->filesystem->files(config('logreader.path')))->filter(function ($file) {
            $this->files[] = $file;
        });

        return $this;
    }

    public function getFileLines($file)
    {
        return collect(explode("\n", $this->filesystem->get($file->getPathname())));
    }

    public function all()
    {
        $this->time = null;

        return $this;
    }

    public function yesterday()
    {
        $this->time = now()->yesterday();

        return $this;
    }

    protected function getTime()
    {
        if (is_null($this->time)) {
            return;
        }

        return $this->time->format('Y-m-d');
    }
}
