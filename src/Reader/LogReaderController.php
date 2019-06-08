<?php

namespace Cion\LaravelLogReader\Reader;

use Illuminate\Routing\Controller;

class LogReaderController extends Controller
{
    public function index(LogReader $loggers)
    {
        if (request()->wantsJson()) {
            return response()->json($loggers->read()->toArray());
        }

        return view('logreader::index');
    }
}
