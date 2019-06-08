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
    }

    public function read()
    {
        $this->getLogFiles()->each(function ($file) {
            $this->getFileLines($file)->each(function ($line) {
                $this->handleFileLine($line);
            });
        });

        return $this;
    }

    public function handleFileLine($line)
    {
        if ($lineHandler = (new LineReader)->read($line)) {
            $this->loggers[] = $lineHandler->getArray();
        }
    }

    public function toArray()
    {
        return array_reverse($this->loggers);
    }

    public function toDatabase()
    {
        // specify table or model
    }

    public function getLogFiles()
    {
        if (! $this->filesystem->exists($this->getPath())) {
            throw new FolderNotFoundException();
        }

        return $this->getDiretoryFiles()->getSubDirectoriesFiles()->getFiles();
    }

    public function getFiles()
    {
        return collect($this->files)->filter(function ($file) {
            if (! is_null($this->getTime())) {
                return $file->getFilename() === "laravel-{$this->getTime()}.log";
            }

            return true;
        });
    }

    public function getSubDirectoriesFiles()
    {
        collect($this->filesystem->directories($this->getPath()))->map(function ($directory) {
            collect($this->filesystem->files($directory))->filter(function ($file) {
                $this->files[] = $file;
            });
        });

        return $this;
    }

    public function getDiretoryFiles()
    {
        collect($this->filesystem->files($this->getPath()))->filter(function ($file) {
            $this->files[] = $file;
        });

        return $this;
    }

    public function getFileLines($file)
    {
        return collect(explode("\n", $this->filesystem->get($file->getPathname())));
    }

    public function setTime($time)
    {
        switch ($time) {
            case 'yesterday':
                return $this->yesterday();
                break;
            case 'all':
                return $this->all();
                break;
            default:
                return $this->today();
        }
    }

    public function all()
    {
        $this->time = null;

        return $this;
    }

    public function today()
    {
        $this->time = now();

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
            return null;
        }

        return $this->time->format('Y-m-d');
    }

    public function getPath()
    {
        return config('logreader.path', storage_path('logs'));
    }
}
