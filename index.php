<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<html>
  <head>
    <title><?=$title?></title>
    <?php print modulekit_to_javascript(); /* pass modulekit configuration to JavaScript */ ?>
    <?php print modulekit_include_js(); /* prints all js-includes */ ?>
    <?php print modulekit_include_css(); /* prints all css-includes */ ?>
  </head>
  <body>
<?
$db=new SQLite3("{$cache}/db.db");

$path_parts=array();
if(isset($_REQUEST['p'])) {
  $path=$_REQUEST['p'];
  $path_parts=explode("/", $path);
}

if(sizeof($path_parts)) {
  try {
    $last_dir=new _archive($path_parts[0]);

    for($i=1; $i<sizeof($path_parts); $i++) {
      $dir=new _directory($path_parts[$i], $last_dir);
      $last_dir=$dir;
    }
  }
  catch(Exception $e) {
    echo "ERROR: ".$e->getMessage();
    exit;
  }
}

print $dir->print_link();

if((sizeof($path_parts)==0)||(!isset($paths[$path_parts[0]]))) {
  print "<ul>\n";
  foreach($paths as $archive_id=>$archive_conf) {
    print "<li><a href='?p={$archive_id}'>{$archive_conf['name']}</a></li>\n";
  }
  print "</ul>\n";
}

?>
  </body>
</html>
