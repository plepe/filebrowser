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
$path_parts=array();
if(isset($_REQUEST['p'])) {
  $path=$_REQUEST['p'];
  $path_parts=explode("/", $path);
}

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
