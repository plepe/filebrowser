<?
$ob_root=null;

class _root extends _directory {
  function __construct() {
    global $db;
  }

  function url() {
    return "?p=";
  }

  function path() {
    return "/";
  }

  function name() {
    return "Root";
  }

  function print_link_path() {
    $ret="";

    $ret.="<li class='root'>".$this->print_link()."</li>\n";

    return $ret;
  }
}

function get_root() {
  global $ob_root;

  if($ob_root===null)
    $ob_root=new _root();

  return $ob_root;
}
