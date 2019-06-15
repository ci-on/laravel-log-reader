<?php

namespace Cion\LaravelLogReader\Reader;

use Illuminate\Support\Str;

class LineHelper
{
    protected $line;

    public function __construct($line)
    {
        $this->line = $line;
    }

    public function getDate()
    {
        return substr($this->line, strpos($this->line, '[') + 1, strpos($this->line, ']') - 1);
    }

    public function hasDate()
    {
        return (bool) strtotime($this->getDate());
    }

    public function hasType($type)
    {
        return strpos($this->line, $this->getEnv().'.'.$type) !== false;
    }

    public function getLogMessageOf($type)
    {
        return Str::replaceFirst($this->getEnv().'.'.$type.': ', '', $this->lineWithoutDate());
    }

    public function lineWithoutDate()
    {
        return substr($this->line, strpos($this->line, ']') + 2);
    }

    public function getEnv()
    {
        return config('logreader.env', env('APP_ENV'));
    }
}
