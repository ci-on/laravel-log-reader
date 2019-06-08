<?php

namespace Cion\LaravelLogReader\Reader;

use Carbon\Carbon;
use Illuminate\Support\Str;

class LineReader
{
    protected $line;

    protected $types = [
        'DEBUG',
        'EMERGENCY',
        'ALERT',
        'CRITICAL',
        'ERROR',
        'WARNING',
        'NOTICE',
        'INFO',
    ];

    public $type;

    public $date;

    public $message;

    public $env;

    public function __construct()
    {
        if (request()->filled('logreader_type') && request('logreader_type') !== 'all') {
            $this->types = [strtoupper(request()->logreader_type)];
        }
    }

    public function read($line)
    {
        $this->line = $line;

        if ($this->isLogger() && $this->hasAtLeastOneLoggerType()) {
            $this->retrieveMessage();

            return $this;
        }

        return false;
    }

    public function toArray()
    {
        return [
            'date' => $this->date,
            'type' => $this->type,
            'message' => $this->message,
        ];
    }

    public function hasAtLeastOneLoggerType()
    {
        return collect($this->types)->filter(function ($type) {
            if ($this->hasLoggerType($type)) {
                return true;
            }
        })->isNotEmpty();
    }

    public function isLogger()
    {
        $date = substr($this->line, 0, strpos($this->line, ']'));
        $date = Str::replaceFirst(']', '', $date);
        $date = Str::replaceFirst('[', '', $date);

        try {
            $this->date = Carbon::parse($date);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function LineWithoutDate()
    {
        return substr($this->line, strpos($this->line, ']') + 2);
    }

    public function retrieveMessage()
    {
        $this->message = Str::replaceFirst($this->getEnv().'.'.$this->type.': ', '', $this->LineWithoutDate());
    }

    public function hasLoggerType($type)
    {
        if (strpos($this->line, $this->getEnv().'.'.$type) !== false) {
            $this->type = $type;

            return true;
        }

        return false;
    }

    public function getEnv()
    {
        return config('logreader.env', env('APP_ENV'));
    }
}
