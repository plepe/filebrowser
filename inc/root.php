<?
$ob_root=null;

class _root extends _item {
  function __construct() {
    parent::__construct("", null);
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

  function content() {
    global $paths;
    if(isset($this->content))
      return $this->content;

    $this->content=array();
    foreach($paths as $path_id=>$path_data) {
      $this->content[]=new _archive($path_id);
    }

    return $this->content;
  }

  function print_content() {
    $ret="";

    $ret.="<ul class='content'>\n";
    foreach($this->content() as $item) {
      $ret.=$item->print_entry();
    }

    $ret.="</ul>\n";

    return $ret;
  }
}

function get_root() {
  global $ob_root;

  if($ob_root===null)
    $ob_root=new _root();

  return $ob_root;
}
