<?php
require_once __DIR__ . '/lib/httpconnection.php';
require_once __DIR__ . '/lib/httpresponse.php';
require_once __DIR__ . '/lib/oauth2client.php';

if (is_file(__DIR__ . '/config.php')) {
  require_once __DIR__ . '/lib/configbase.php';
  require_once __DIR__ . '/config.php';
}
// simple log wraps error_log, 
function slog($message)
{
  if (is_array($message)) {
    foreach($message as $k => $v) {
      if (is_array($v))
      {
        slog("{$k}=> ");
        slog($v);
      } else {
        slog("{$k} => {$v}");
      }
    }
  } else {
    error_log($message."\n", 3, ConfigFlattr::$LOGFILE);
  }
}	  

function h($str)
{
  return htmlentities($str,ENT_QUOTES, "UTF-8");
}

if (php_sapi_name() != 'cgi' && !empty($_SERVER['REMOTE_ADDR'])) {
  session_start();
  header('Content-type: text/html; charset=utf-8');
}
