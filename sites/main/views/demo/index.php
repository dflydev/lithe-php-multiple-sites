<?php
$s->shell('myshell.php');
$s->set('title', 'Demo Home');
$uri = $s->helper('uri');
?>
<ul>
<li><a href="<?php $s->p($uri->get()); ?>">Home</a></li>
</ul>
<h1>Demonstration</h1>
<form action="<?php $s->p($uri->get('demo/submit')); ?>" method="post">
<fieldset>
<legend>Form handling</legend>
<label for="demo-form-name">Name</label><input id="demo-form-name" name="name" /><input type="submit" value="Test" />
</fieldset>
</form>