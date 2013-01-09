<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?
$db=new SQLite3("{$cache}/db.db");

$item=get_item((isset($_REQUEST['p'])?$_REQUEST['p']:null));

$stat=$item->stat_thumbnail();
Header("Content-Type: {$stat['mime_type']}");

Header("Content-Disposition: inline; filename=\"thumb-{$item->name()}\"");

print $item->get_thumbnail();
