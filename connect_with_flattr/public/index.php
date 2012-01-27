<?php
require __DIR__ . '/../bootstrap.php';

function on_get()
{
  $client = new OAuth2Client(ConfigFlattr::all());
  $vars = array(
    'authorize_url' => $client->authorizeUrl(),
    'title'         => 'Connect with flattr sample application',
  );
  return template(basename(__FILE__),$vars);
}

run(basename(__FILE__),'.php');
