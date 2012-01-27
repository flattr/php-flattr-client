<?php
define('PROJECT_PATH', dirname(__FILE__));
require PROJECT_PATH . '/coltrane/coltrane.php';

// require oauth2client, httpconnection, httpresponse and configbase
require_libs(__DIR__.'/../lib');
// include the api config
require __DIR__.'/../config.php';

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
