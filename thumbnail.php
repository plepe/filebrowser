<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php
$db=new SQLite3("{$cache}/db.db");
$file_browser = new FileBrowser($paths);

$item=$file_browser->get_item((isset($_REQUEST['p'])?$_REQUEST['p']:null));

$stat=$item->stat_thumbnail();
Header("Content-Type: {$stat['mime_type']}");

Header("Content-Disposition: inline; filename=\"thumb-{$item->name()}\"");

print $item->get_thumbnail();
