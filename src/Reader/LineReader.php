<?php

namespace Mohamed\LaravelLogReader\Reader;

use Carbon\Carbon;
use Illuminate\Support\Str;

class LineReader {

    protected $line;

    protected $typeLogger = [
        'DEBUG',
        'EMERGENCY',
        'ALERT',
        'CRITICAL',
        'ERROR',
        'WARNING',
        'NOTICE',
        'INFO'
    ];

    public $type;

    public $date;

    public $message;

    public function __construct($line)
    {
        $this->line = $line;
    }

    public function handle()
    {
        if($this->isLogger() && $this->hasAtLeastOneLoggerType()) {
            $this->retrieveDate();

            $this->retrieveMessage();

            return $this;
        }

        return false;
    }

    public function getArray()
    {
        return [
            "date" => $this->date,
            "type" => $this->type,
            "message" => $this->message
        ];
    }

    public function hasAtLeastOneLoggerType()
    {
        return collect($this->typeLogger)->filter(function($type) {
            if ($this->hasLoggerType($type)) {
                return true;
            }
        })->isNotEmpty();
    }

    public function isLogger()
    {
        return (bool) (strpos($this->line, '['. now()->format('Y-m-d')) !== false);
    }

    public function retrieveDate()
    {
        $date = substr($this->line, 0, strpos($this->line, ']'));
        $date = Str::replaceFirst(']', '', $date);
        $date = Str::replaceFirst('[', '', $date);

        $this->date = Carbon::parse($date);
    }

    public function LineWithoutDate()
    {
        return substr($this->line, strpos($this->line, ']') + 2);
    }

    public function retrieveMessage()
    {
        $this->message = Str::replaceFirst($this->type.": ", '', $this->LineWithoutDate());
    }

    public function hasLoggerType($type)
    {
        // config('logreader.env')
        if(strpos($this->line, 'local'.'.'.$type) !== false) {
            $this->type = 'local'.'.'.$type;

            return true;
        }

        return false;
    }
}
