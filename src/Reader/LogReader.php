<?php
declare(strict_types = 1);

namespace Cion\LaravelLogReader\Reader;

use Cion\LaravelLogReader\Reader\Exception\FolderNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\SplFileInfo;

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

    public function read() : LogReader
    {
        $this->setTime();

        $this->getLogFiles()->each(function ($file) {
            $this->getFileLogSections($file)->each(function ($line) {
                $this->handleFileLine($line);
            });
        });

        return $this;
    }

    public function handleFileLine(array $section) : void
    {
        $sectionReader = (new LogSectionReader($section))->read();

        if (! empty($sectionReader->toArray())) {
            $this->loggers[] = $sectionReader->toArray();
        }
    }

    public function toArray() : array
    {
        return array_reverse($this->loggers);
    }

    public function toDatabase()
    {
        // specify table or model
    }

    public function getLogFiles() : Collection
    {
        if (! $this->filesystem->exists($this->getPath())) {
            throw new FolderNotFoundException();
        }

        return $this->getDiretoryFiles()->getSubDirectoriesFiles()->getFiles();
    }

    public function getFiles() : Collection
    {
        return collect($this->files)->filter(function ($file) {
            if ($this->hasTime()) {
                return $file->getFilename() === "laravel-{$this->getTime()}.log";
            }

            return true;
        });
    }

    public function getSubDirectoriesFiles() : LogReader
    {
        collect($this->filesystem->directories($this->getPath()))->map(function ($directory) {
            collect($this->filesystem->files($directory))->filter(function ($file) {
                $this->files[] = $file;
            });
        });

        return $this;
    }

    public function getDiretoryFiles() : LogReader
    {
        collect($this->filesystem->files($this->getPath()))->filter(function ($file) {
            $this->files[] = $file;
        });

        return $this;
    }

    public function getFileLogSections(SplFileInfo $file)
    {
        return $this->retrieveSections($this->filesystem->get($file->getPathname()));
    }

    public function setTime() : void
    {
        switch (request()->logreader_time) {
            case 'yesterday':
                $this->yesterday();
                break;
            case 'all':
                $this->all();
                break;
            default:
                $this->today();
        }
    }

    public function all() : LogReader
    {
        $this->time = null;

        return $this;
    }

    public function today() : LogReader
    {
        $this->time = now();

        return $this;
    }

    public function yesterday() : LogReader
    {
        $this->time = now()->yesterday();

        return $this;
    }

    public function hasTime() : bool
    {
        if (is_null($this->time)) {
            return false;
        }

        return true;
    }

    protected function getTime() : string
    {
        return $this->time->format('Y-m-d');
    }

    public function getPath() : string
    {
        return config('logreader.path', storage_path('logs'));
    }

    public function retrieveSections(string $content) : Collection
    {
        $sections = collect();

        collect(explode("\n", $content))->each(function ($line) use (&$sections) {
            if ((new LineHelper($line))->hasDate()) {
                $sections->push([
                    'line' => $line,
                    'extra' => []
                ]);
            } else {
                $sections = $this->addToLastSection($sections, $line);
            }
        });

        return $sections;
    }

    public function addToLastSection(Collection $sections, string $line)
    {
        $size = sizeof($sections);

        return $sections->map(function ($value, $key) use ($size, $line) {
            if ($size - 1 === $key) {
                $value['extra'][] = $line;
            }

            return $value;
        });
    }
}
