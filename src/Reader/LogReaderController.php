<?php

namespace Cion\LaravelLogReader\Reader;

use Illuminate\Routing\Controller;

class LogReaderController extends Controller
{
    public function index(LogReader $loggers)
    {
        if (request()->filled('logreader_time')) {
            $loggers = $loggers->setTime(request()->logreader_time);
        }

        if (request()->wantsJson()) {
            return response()->json($loggers->read()->toArray());
        }

        return view('logreader::index');
    }
}
