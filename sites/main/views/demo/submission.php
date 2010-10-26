<?php
$s->shell('myshell.php');
$s->set('title', 'Demo Form Submission Results');
$uri = $s->helper('uri');
?>
<ul>
<li><a href="<?php $s->p($uri->get()); ?>">Home</a></li>
<li><a href="<?php $s->p($uri->get('demo')); ?>">Demo</a></li>
</ul>
<h1>Demonstration Submission results</h1>
You posted <strong><?php $s->p($name ? $name : 'NOTHING'); ?></strong>. <a href="<?php $s->p($uri->get('demo')); ?>">Again?</a>