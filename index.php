<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?
session_start();
Header("Content-Type: text/html; charset=utf-8");
?>
<html>
  <head>
    <title><?=$title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php print modulekit_to_javascript(); /* pass modulekit configuration to JavaScript */ ?>
    <?php print modulekit_include_js(); /* prints all js-includes */ ?>
    <?php print modulekit_include_css(); /* prints all css-includes */ ?>
  </head>
  <body>
<?
$db=new SQLite3("{$cache}/db.db");

if(isset($_REQUEST['search'])) {
  $item=get_search($_REQUEST['search']);
}
else {
  $item=get_item((isset($_REQUEST['p'])?$_REQUEST['p']:null));
}

$available_modes=array("list", "symbols");
if(isset($_REQUEST['mode'])&&in_array($_REQUEST['mode'], $available_modes))
  $_SESSION['mode']=$_REQUEST['mode'];
elseif(!isset($_SESSION['mode']))
  $_SESSION['mode']=$available_modes[0];

$item->update();

print "<div class='content'>\n";
print "<h1>".$item->name()."</h1>\n";
print "<div class='item ".$item->type()." mode_{$_SESSION['mode']}'>\n";
print $item->print_content();
print "</div>\n";
print "</div>\n";

print "<div class='info'>\n";
print "<h2>Path</h2>\n";
print "<ul>\n";
print $item->print_link_path();
print "</ul>\n";

print "<h2>Search</h2>\n";
print "<form method='get'>\n";
$html_search="";
if(isset($_REQUEST['search']))
  $html_search=htmlspecialchars($_REQUEST['search']);
print "<input type='text' name='search' value=\"{$html_search}\">\n";
print "</form>\n";

print "<h2>View</h2>\n";
print "<form method='get'>\n";
print "<input type='hidden' name='p' value=\"{$_REQUEST['p']}\">\n";
print "<select name='mode' onChange='form.submit()'>\n";
foreach($available_modes as $mode) {
  print "  <option value='$mode'";
  if($_SESSION['mode']==$mode)
    print " selected";
  print ">$mode</option>\n";
}
print "</select>\n";
print "</form>\n";

$info=$item->print_info();
if($info) {
  print "<h2>Info</h2>\n";
  print $info;
}

print "</div>\n";

?>
  </body>
</html>
