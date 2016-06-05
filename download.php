<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php
$db=new SQLite3("{$cache}/db.db");

$item=get_item((isset($_REQUEST['p'])?$_REQUEST['p']:null));

$stat=$item->file_stat();
Header("Content-Type: {$stat['mime_type']}");

if(preg_match("/download\.php$/", $_SERVER['SCRIPT_NAME']))
  $disposition_mode="attachment";
else
  $disposition_mode="inline";

Header("Content-Disposition: {$disposition_mode}; filename=\"{$item->name()}\"");

$f=$item->fopen("r");
while($r=fread($f, 1024*1024))
  print $r;
fclose($f);
