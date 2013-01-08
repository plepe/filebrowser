<?
class _item {
  function __construct($path_part, $parent) {
    $this->parent=$parent;
    $this->path_part=$path_part;
  }

  function path() {
    return $this->parent->path().$this->path_part."/";
  }

  function url($options=array()) {
    return $this->parent->url($options)."/".urlencode($this->path_part);
  }

  function download_url($options=array()) {
    $options['_file']="download.php/{$this->name()}";

    return $this->url($options);
  }


  function view_url($options=array()) {
    $options['_file']="view.php/{$this->name()}";

    return $this->url($options);
  }

  function print_link() {
    return "<a href='".$this->url()."'>".
      htmlspecialchars($this->name()).
      "</a>";
  }

  function type() {
    throw new Exception("type() not overridden!");
  }

  function print_link_path() {
    $ret="";

    if($this->parent)
      $ret.=$this->parent->print_link_path();

    $ret.="<li class='".$this->type()."'>".$this->print_link()."</li>\n";

    return $ret;
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

  function print_entry() {
    return "<li>".$this->print_link()."</li>\n";
  }

  function print_info() {
  }
}

function __item_discard_directory($part) {
  if($part=="")
    return false;

  return true;
}

function get_item($path=null) {
  global $paths;

  if($path===null)
    return get_root();

  $path_parts=array();
  $path_parts=explode("/", $path);
  $path_parts=array_filter($path_parts, "__item_discard_directory");

  if(sizeof($path_parts)) {
    try {
      $item=new _archive($path_parts[0]);

      for($i=1; $i<sizeof($path_parts)-1; $i++) {
        $item=new _directory($path_parts[$i], $item);
      }

      if(sizeof($path_parts)>1) {
        try {
          $item=new _file($path_parts[sizeof($path_parts)-1], $item);
        }
        // if last part is not a file, try to load directory
        catch(Exception $e) {
          $item=new _directory($path_parts[sizeof($path_parts)-1], $item);
        }
      }
    }
    catch(Exception $e) {
      echo "ERROR: ".$e->getMessage();
      exit;
    }
  }
  else
    $item=get_root();

  return $item;
}
