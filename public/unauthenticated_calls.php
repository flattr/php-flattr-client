<?php
require __DIR__.'/../bootstrap.php';

$client = new OAuth2Client(ConfigFlattr::all());

// fetch a specific thing based on id; more info at http://developers.flattr.net/v2/#things
$thing = $client->getParsed('/things/187509');

// lookup if the url is already submited to flattr
$lookup = $client->getParsed('/things/lookup/q/'.urlencode('http://blog.flattr.net/2011/10/api-v2-beta-out-whats-changed/'));

// fetch a user; more info at http://developers.flattr.net/v2/#users
$user = $client->getParsed('/users/flattr');
?>
<div><a href="/">home</a></div>

/thing/187509/flattr-on-Flattr:
<pre><?php var_dump($thing);?></pre>
<hr/>our(flattr) profile on flattr.com:
<pre><?php var_dump($user);?></pre>
<hr/>
a /lookup of http://blog.flattr.net/2011/10/api-v2-beta-out-whats-changed/ returned
<pre><?php var_dump($lookup)?></pre>
