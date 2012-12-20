<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?
$db=new SQLite3("{$cache}/db.db");

$item=get_item((isset($_REQUEST['p'])?$_REQUEST['p']:null));

$stat=$item->file_stat();
Header("Content-Type: {$stat['mime_type']}");

$f=$item->fopen("r");
while($r=fread($f, 1024*1024))
  print $r;
fclose($f);
