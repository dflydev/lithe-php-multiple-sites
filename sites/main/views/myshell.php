<!DOCTYPE html>
<html>
<head>
<?php
$defaultTitle = 'Lithe';
if ( ! isset($title) ) { $title = $defaultTitle; }
else { $title = $title . ' | ' . $defaultTitle; }
?>
    <title><?php $s->p($title); ?></title>
</head>
<body>
<?php echo $skittleViewContent; ?>
</body>
</html>
