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

function discard_directory($part) {
  if($part=="")
    return false;

  return true;
}

$path_parts=array();
if(isset($_REQUEST['p'])) {
  $path=$_REQUEST['p'];
  $path_parts=explode("/", $path);
  $path_parts=array_filter($path_parts, "discard_directory");
}

if(sizeof($path_parts)) {
  try {
    $item=new _archive($path_parts[0]);

    for($i=1; $i<sizeof($path_parts)-1; $i++) {
      $item=new _directory($path_parts[$i], $item);
    }

    if(sizeof($path_parts)>1) {
      try {
        $item=new _file($path_parts[sizeof($path_parts)-1], $item);
      }
      // if last part is not a file, try to load directory
      catch(Exception $e) {
        $item=new _directory($path_parts[sizeof($path_parts)-1], $item);
      }
    }
  }
  catch(Exception $e) {
    echo "ERROR: ".$e->getMessage();
    exit;
  }
}
else
  $item=get_root();

print $item->print_link()."<br>\n";
print "<ul>\n";
print $item->print_link_path();
print "</ul>\n";

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
