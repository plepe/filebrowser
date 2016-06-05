<?php
$ob_root=null;

class FileBrowserRoot extends FileBrowserItem {
  function __construct() {
    parent::__construct("", null);
  }

  function url($param=array(), $options=array()) {
    $ret="";

    if(isset($options['_file']))
      $ret.=$options['_file'];

    $str=array();
    foreach($param as $k=>$v)
      if($k=="p")
        $str[]=urlencode($k)."=".$v;
      else
        $str[]=urlencode($k)."=".urlencode($v);

    $ret.="?".implode("&", $str);

    return $ret;
  }

  function item_path() {
    return "";
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
      $this->content[]=new FileBrowserArchive($path_id);
    }

    return $this->content;
  }
}

function file_browser_get_root() {
  global $ob_root;

  if($ob_root===null)
    $ob_root=new FileBrowserRoot();

  return $ob_root;
}
