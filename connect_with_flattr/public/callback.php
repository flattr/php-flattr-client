<?php
require __DIR__ . '/../bootstrap.php';

// on_before are always runned if defined
function on_before( $params )
{
  $_SESSION['access_token'] = null;
  $client = new OAuth2Client(ConfigFlattr::all());
  if (!empty($params['code'])) {
    try {
      $_SESSION['access_token'] = $client->fetchAccessToken($params['code']);
      $user = $client->getParsed('/user');
    } catch(Exception $e)
    {
      slog('failed to get access token: '.$e->getMessage());
    }
  }

  if ( ! empty($_SESSION['access_token']) && ($user = $client->getParsed('/user'))) {
    $_SESSION['flattr_username'] = $user['username'];
    flash('successfully authenticated as '.$user['username'], 'notice');
    redirect('/authenticated.php');
  } else {
    flash('failed to get an access token','alert');
    redirect('/');
  }
}
run(basename( __FILE__ ),'.php');
