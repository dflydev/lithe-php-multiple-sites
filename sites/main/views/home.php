<?php
$s->shell('myshell.php');
$s->set('title', 'Welcome!');
$uri = $s->helper('uri');
?>
<h1>Welcome to Lithe!</h1>
<ul>
<li><a href="<?php $uri->p('demo'); ?>">Demo controller</a></li>
</ul>
