<?php

namespace Mohamed\LaravelLogReader\Reader;

use Illuminate\Routing\Controller;

class LogReaderController extends Controller
{
    public function index(LogReader $loggers)
    {
        if (request()->filled('logreader_time')) {
            switch (request()->logreader_time) {
                case 'yesterday':
                    $loggers = $loggers->yesterday();
                    break;
                case 'all':
                    $loggers = $loggers->all();
                    break;
            }
        }

        if (request()->wantsJson()) {
            return response()->json($loggers->read()->toArray());
        }

        return view('logreader::index');
    }
}
