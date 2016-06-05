<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?
session_start();
Header("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?=$title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php print modulekit_to_javascript(); /* pass modulekit configuration to JavaScript */ ?>
    <script type='text/javascript' src='lib/jquery.min.js'></script>
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

$available_modes=array("list", "symbols", "details");
if(isset($_REQUEST['mode'])&&in_array($_REQUEST['mode'], $available_modes))
  $_SESSION['mode']=$_REQUEST['mode'];
elseif(!isset($_SESSION['mode']))
  $_SESSION['mode']=$available_modes[0];

$db->query("begin transaction");
$item->update();
$db->query("end transaction");

print "<div class='content'>\n";
print "<h1>".$item->name()."</h1>\n";
print "<div id='item' class='item ".$item->type()." mode_{$_SESSION['mode']}'>\n";
print $item->print_content($_REQUEST);
print "</div>\n";
print "</div>\n";

print "<div class='info'>\n";
print "<h2>Path</h2>\n";
print "<ul>\n";
print $item->print_link_path();
print "</ul>\n";

if($item->parent) {
  $content = $item->parent->content();
  $index = $item->parent->get_index($item);

  if($index !== null) {
    if($index == 0) {
      print "<img src='lib/tango/scalable/actions/go-previous.svg'/>";
    }
    else {
      $other_item = $content[$index - 1];
      print "<a href='" . htmlspecialchars($other_item->url())  . "' title='" . htmlspecialchars($other_item->name()) . "'><img src='lib/tango/scalable/actions/go-previous.svg'/></a>";
    }

    print "&nbsp;";

    if($index >= sizeof($content) - 1) {
      print "<img src='lib/tango/scalable/actions/go-next.svg'/>";
    }
    else {
      $other_item = $content[$index + 1];
      print "<a href='" . htmlspecialchars($other_item->url())  . "' title='" . htmlspecialchars($other_item->name()) . "'><img src='lib/tango/scalable/actions/go-next.svg'/></a>";
    }
  }
}

print "<h2>Search</h2>\n";
print "<form method='get'>\n";
$html_search="";
if(isset($_REQUEST['search']))
  $html_search=htmlspecialchars($_REQUEST['search']);
print "<input type='text' name='search' value=\"{$html_search}\">\n";
print "</form>\n";

print "<h2>View</h2>\n";
print "<form id='view' method='get'>\n";
print "<input type='hidden' name='p' value=\"{$_REQUEST['p']}\">\n";
print "<select name='mode' onChange='change_view_mode()'>\n";
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
