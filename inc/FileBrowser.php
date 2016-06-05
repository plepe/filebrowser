<?php
class FileBrowser extends FileBrowserItem {
  function __construct($paths) {
    $this->paths = $paths;
    $this->parent = null;
    $this->root = $this;
    $this->path_part = "";
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
    if(isset($this->content))
      return $this->content;

    $this->content=array();
    foreach($this->paths as $path_id=>$path_data) {
      $this->content[]=new FileBrowserArchive($this, $path_id);
    }

    return $this->content;
  }
  function get_item($path=null) {
    if($path===null)
      return $this;

    $path_parts=array();
    $path_parts=explode("/", $path);
    $path_parts=array_filter($path_parts, "__item_discard_directory");

    if(sizeof($path_parts)) {
      try {
        $item=new FileBrowserArchive($this, $path_parts[0]);

        for($i=1; $i<sizeof($path_parts)-1; $i++) {
          $item = new FileBrowserDirectory($item, $path_parts[$i]);
        }

        if(sizeof($path_parts)>1) {
          try {
            $item = new FileBrowserFile($item, $path_parts[sizeof($path_parts)-1]);
          }
          // if last part is not a file, try to load directory
          catch(Exception $e) {
            $item = new FileBrowserDirectory($item, $path_parts[sizeof($path_parts)-1]);
          }
        }
      }
      catch(Exception $e) {
        echo "ERROR: ".$e->getMessage();
        exit;
      }
    }
    else
      $item = $this;

    return $item;
  }

  function get_directory($id) {
    global $db;

    if(is_integer($id)) {
      $sql_id=$db->escapeString($id);
      $res=$db->query("select * from directory where directory_id='{$sql_id}'");
    }
    else {
      $sql_path=$db->escapeString($id);
      $res=$db->query("select * from directory where path='{$sql_path}'");
    }

    $data=$res->fetchArray();

    return $this->get_item("{$data['archive_id']}{$data['path']}");
  }

  function get_search($text) {
    return new FileBrowserSearch($this, $text);
  }
}

function __item_discard_directory($part) {
  if($part=="")
    return false;

  return true;
}
