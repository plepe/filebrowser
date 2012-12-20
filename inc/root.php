<?
$ob_root=null;

class _root extends _item {
  function __construct() {
    parent::__construct(null);
  }

  function url() {
    return "?p=";
  }

  function path() {
    return "";
  }

  function name() {
    return "Root";
  }

  function type() {
    return "root";
  }
}

function get_root() {
  global $ob_root;

  if($ob_root===null)
    $ob_root=new _root();

  return $ob_root;
}
