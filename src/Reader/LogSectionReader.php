<?php

declare(strict_types=1);

namespace Cion\LaravelLogReader\Reader;

use Carbon\Carbon;

class LogSectionReader
{
    protected $lineHelper;

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

    public $extra;

    public function __construct(array $section)
    {
        $this->lineHelper = new LineHelper($section['line']);

        $this->extra = collect($section['extra']);
    }

    public function read() : self
    {
        $this->retrieveSectionInformations();

        return $this;
    }

    public function handleType() : void
    {
        if (request()->filled('logreader_type') && request('logreader_type') !== 'all') {
            $this->types = [strtoupper(request()->logreader_type)];
        }

        foreach ($this->types as $type) {
            if ($this->lineHelper->hasType($type)) {
                $this->type = $type;
            }
        }
    }

    public function handleDate() : void
    {
        if (strtotime($date = $this->lineHelper->getDate())) {
            $this->date = Carbon::parse($date);
        }
    }

    public function handleMessage() : void
    {
        $this->message = $this->lineHelper->getLogMessageOf($this->type);
    }

    public function retrieveSectionInformations() : void
    {
        $this->handleDate();

        $this->handleType();

        $this->handleMessage();

        $this->handleExtra();
    }

    public function handleExtra() : void
    {
        // Remove Useless Informations
        $this->extra->shift();
        $this->extra->pop();
        $this->extra->pop();

        $this->extra = $this->extra->map(function ($extra) {
            return explode(': ', substr($extra, 3));
        });
    }

    public function toArray() : array
    {
        if (! $this->date || ! $this->type || ! $this->message || ! $this->extra) {
            return [];
        }

        return [
            'date' => $this->date,
            'type' => $this->type,
            'message' => $this->message,
            'extra' => $this->extra->toArray(),
        ];
    }
}
