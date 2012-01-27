<div class="error">
	error!
	<?php if (isset($render_me)) echo "tried to render <strong>".h($render_me)."</strong> but did not found it."; ?>
</div>
passed params:
<?php var_dump($p); ?>
