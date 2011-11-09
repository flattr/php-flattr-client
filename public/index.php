<?php
require __DIR__ . '/../bootstrap.php';
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_destroy();
    header('Location: index.php');
}
$client = new OAuth2Client(ConfigFlattr::all()); // configFlattr is defined in ../config.php
if (isset($_GET['msg'])) {
  echo '<div>'.htmlentities($_GET['msg']).'</div>';
}
?>
<a href="/index.php?logout=true">logout</a><br/>
<a href="<?= $client->authorizeUrl()?>">Authenticate</a><br/>
<a href="/unauthenticated_calls.php">Make calls as the API client</a>
