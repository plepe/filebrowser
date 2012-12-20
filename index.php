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

$item=get_item((isset($_REQUEST['p'])?$_REQUEST['p']:null));

print $item->print_link()."<br>\n";
print "<ul>\n";
print $item->print_link_path();
print "</ul>\n";

print $item->print_content();

?>
  </body>
</html>
