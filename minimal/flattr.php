<?php
require_once __DIR__ . '/../bootstrap.php';

if ( empty($_SESSION['access_token']) ) {
  header('index.php');
  exit;
}

$thingIdList= array('466757','465172','467437');

$client = new OAuth2Client( ConfigFlattr::all(array('access_token' => $_SESSION['access_token'])) );

if (!empty($_GET['thing_url']))
{
  $thingUrl = $_GET['thing_url'];
  echo "will try to flattr ".$thingUrl;
  $response = Oauth2Client::parseResponse($client->post('/flattr', array('url' => $thingUrl)));
  var_dump($response);
  exit;
}

$profile = $client->getParsed('/user');

$things = array();
foreach($thingIdList as $thingId) {
  $things[] = $client->getParsed('/things/'.$thingId);
}
?>
<a href="/index.php?logout=true">logout</a>
<a href="/authenticated_calls.php">profile</a>
<a href="/submit.php">submit new thing</a>
<h1>Authenticated as as <?php echo h($profile['username'])?> </h1>
<hr/>

<?php foreach($things as $thing) { ?>
  <div>
    <?php if ($thing['flattred']) { ?>
      <?php echo h($thing['url'])?> is already flattred by the authenticated user
    <?php } else { ?>
      <a href="/flattr.php?thing_url=<?php echo h($thing['url'])?>">flattr <?php echo h($thing['title'])?></a>
    <?php } ?>
  </div>
<?php } ?>
