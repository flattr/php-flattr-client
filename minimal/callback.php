<?php

require dirname(__FILE__) . '/../bootstrap.php';
if (!empty($_GET['code'])) {
    $client = new OAuth2Client(ConfigFlattr::all());
    $_SESSION['access_token'] = $client->fetchAccessToken($_GET['code']);
} else {
    $msg = isset($_GET['error'])? $_GET['error'] : 'no code and no error';
    header('Location: index.php?msg='.$msg);
    exit;
}
$user = $client->getParsed('/user');
?>

<a href="/index.php?logout=true">logout</a><br/>
<h1>user:</h1>
<pre><?php var_dump($user);?></pre>
<a href="/authenticated_calls.php">make some authenticated calls</a>
