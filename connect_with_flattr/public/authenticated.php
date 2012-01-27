<?php
require __DIR__ . '/../bootstrap.php';

function on_before($params)
{
  if (empty($_SESSION['flattr_username']))
  {
    flash('You are not authenticated, please connect with flattr.','alert');
    redirect('/');
  }
  $params['client'] = new OAuth2Client(ConfigFlattr::all(array('access_token' => $_SESSION['access_token'])));
  return $params;
}

function on_get($params)
{
  $vars = array(
    'profile' => $params['client']->getParsed('/user'),
    'things'  => $params['client']->getParsed('/user/things'),
  );
  return template(basename(__FILE__),$vars);
}
run(basename(__FILE__),'.php');
