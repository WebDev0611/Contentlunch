<?php

class ErrorLogController extends BaseController {

  public function index()
  {
    return ErrorLog::all();
  }

    public function store() {
        $log = new ErrorLog();
        $log->error = Input::get('log');
        $log->save();

        return ['success' => 1];
    }

    public function show($id) {
        $log = ErrorLog::findOrFail($id);

        $log = json_decode($log->error, true);
        foreach($log['session'] as $key => $value) {
            $log['session'][$key] = json_decode($value, true);
        }

        var_dump($log);
    }

}
