<?php
require_once __DIR__ . '/../bootstrap.php';

if ( empty($_SESSION['access_token']) ) {
  header('index.php');
}
$client = new OAuth2Client( ConfigFlattr::all(array('access_token' => $_SESSION['access_token'])) );
$profile = $client->getParsed('/user');
$flattrs = $client->getParsed('/user/flattrs');
$things = $client->getParsed('/user/things');
?>

<a href="/index.php?logout=true">logout</a>
<a href="/submit.php">submit new thing</a>
<h1>Authenticated as as</h1>
<pre><?php var_dump($profile)?></pre>

<hr/>
flatterings:
<pre><?php var_dump($flattrs)?></pre>
<hr/>
things:
<pre><?php var_dump($things)?></pre>


