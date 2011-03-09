<?php

class pkSpeedLog
{
  static public function register()
  {
    register_shutdown_function(array('pkSpeedLog', 'log'), microtime(true));
  }
  
  static public function log($start)
  {
    $end = microtime(true);
    $elapsed = $end - $start;
    $speedLog = new SpeedLog();
    $speedLog->created_at = aDate::mysql($start);
    $speedLog->elapsed = $elapsed;
    $speedLog->request = $_SERVER['REQUEST_URI'];
    $speedLog->save();
  }
}

