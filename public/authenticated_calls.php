<?php
require_once __DIR__ . '/../bootstrap.php';

if ( empty($_SESSION['access_token']) ) {
  header('index.php');
  exit;
}
$client = new OAuth2Client( ConfigFlattr::all(array('access_token' => $_SESSION['access_token'])) );

// getParsed is just a get() wrapped in oauth2client::parseResponse()
$profile = $client->getParsed('/user');
$flattrs = $client->getParsed('/user/flattrs');
$things  = $client->getParsed('/user/things');

// proper html is intentially left out to minimize code
?>

<a href="/index.php?logout=true">logout</a>
<a href="/submit.php">submit new thing</a>
<a href="/flattr.php">flattr something</a>
<h1>Authenticated as as <?php echo h($profile['username'])?></h1>

<hr/>
flatterings: <?php echo count($flattrs)?>
<pre><?php var_dump($flattrs)?></pre>
<hr/>
things: <?php echo count($things)?>
<pre><?php var_dump($things)?></pre>
