<?php
require_once __DIR__ . '/../bootstrap.php';

if ( empty($_SESSION['access_token']) ) {
  header('index.php');
}
$client = new OAuth2Client( ConfigFlattr::all(array('access_token' => $_SESSION['access_token'])) );

if (!empty($_POST)) {
    $response = $client->post('/things', $_POST['thing']);
    if (strpos($response->responseCode,'4') === 0) {
        $msg = 'Failed to create thing: <pre>' . $response->body . '</pre>';
    } else if (strpos($response->responseCode,'20') === 0) {
        $data = OAuth2Client::parseResponse($response);
        $msg = 'thing created with id '.$data['id'].'; message('.$data['message'].') description('.$data['description'].')';
    } else {
        echo 'not sure about the response...';
        $msg = var_export($response,true);
    }
}

$languages = $client->getParsed('/languages');
$categories = $client->getParsed('/categories');

?>

<a href="/index.php?logout=true">logout</a><br/>
<a href="/authenticated_calls.php">profile</a><br/>
<?php if (!empty($msg)) { ?>
  message: <?= htmlentities($msg);?>
<?php } ?>

<form method="post" action="">
<fieldset>
    <input name="thing[url]" value="" placeholder="url"/>
    <input name="thing[title]" value="" placeholder="subject"/>
    <textarea name="thing[description]"></textarea>
    <div>
        category
        <select name="thing[category]">
            <?php foreach( $categories as $category) { ?>
                <option value="<?=$category['id']?>"><?=$category['text']?></option>
            <?php } ?>
        </select>
    </div>
    <div>
        language
        <select name="thing[language]">
            <?php foreach( $languages as $language) { ?>
                <option value="<?=$language['id']?>"><?=$language['text']?></option>
            <?php } ?>
        </select>
    </div>
    <div>tags<input name="thing[tags]"/></div>
    <input type="submit">
</fieldset>
</form>
