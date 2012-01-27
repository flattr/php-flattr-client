<?php
require __DIR__ . '/../bootstrap.php';

function on_before($params)
{
  $_SESSION = array();
  $_SESSION = null;
  session_destroy();
  redirect('/');
}

run(basename(__FILE__),'.php');
