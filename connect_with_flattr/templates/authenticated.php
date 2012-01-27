this is an authenticated page.

<div>
  you are connected as <strong><?= h($p['profile']['username']);?></strong>
</div>
<div>
  <p>
    your things:
  </p>
  <?php foreach($p['things'] as $thing) { ?>
    <?=h($thing['url']);?></br>
  <?php } ?>
</div>

<a href="/logout.php">logout</a>
