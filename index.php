<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?
Header("Content-Type: text/html; charset=utf-8");
?>
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

$item=get_item((isset($_REQUEST['p'])?$_REQUEST['p']:null));

print "<div class='info'>\n";
print "<h2>Path</h2>\n";
print "<ul>\n";
print $item->print_link_path();
print "</ul>\n";

$info=$item->print_info();
if($info) {
  print "<h2>Info</h2>\n";
  print $info;
}

print "</div>\n";

print "<div class='content'>\n";
print "<h1>".$item->name()."</h1>\n";
print $item->print_content();
print "</div>\n";

?>
  </body>
</html>
