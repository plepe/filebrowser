<?
class _item {
  function __construct($path_part, $parent) {
    $this->parent=$parent;
    $this->path_part=$path_part;
  }

  function path() {
    return $this->parent->path().$this->path_part."/";
  }

  function item_path() {
    return $this->parent->item_path()."/".$this->path_part;
  }

  function url($param=array(), $options=array()) {
    $param['p']="/".urlencode($this->path_part).
      (isset($param['p'])?$param['p']:"");

    return $this->parent->url($param, $options);
  }

  function download_url($param=array(), $options=array()) {
    $options['_file']="download.php/{$this->name()}";

    return $this->url($param, $options);
  }


  function view_url($param=array(), $options=array()) {
    $options['_file']="view.php/{$this->name()}";

    return $this->url($param, $options);
  }

  function thumbnail_url($param=array(), $options=array()) {
    return "lib/tango/scalable/places/folder.svg";
  }

  function print_name() {
    return
      strtr(htmlspecialchars($this->name()),
        array("."=>".&shy;", "_"=>"_&shy;", "-"=>"-&shy;"));
  }

  function print_link() {
    $ret="";

    $ret.="<a href='".$this->url()."'>";
    $ret.=$this->print_name();
    $ret.="</a>\n";

    return $ret;
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

    $ret.="<table class='list'>\n";
    foreach($this->content() as $item) {
      $ret.=$item->print_entry();
    }

    $ret.="</table>\n";

    return $ret;
  }

  function print_thumbnail($options=array()) {
    return "<img src='".$this->thumbnail_url(array(), $options)."' />";
  }

  function get_thumbnail($options=array()) {
    return file_get_contents($this->thumbnail_url(array(), $options));
  }

  function stat_thumbnail() {
    return array("mime_type"=>"image/svg+xml");
  }

  function print_entry() {
    $ret="";

    $ret.="<tr>";
    $ret.="<td class='thumbnail'><a href='".$this->url()."'>".
      $this->print_thumbnail()."</a></td>";
    $ret.="<td class='name'><a href='".$this->url()."'>".
      $this->print_name()."</a></td>";
    $ret.="</tr>";

    return $ret;
  }

  function db_remove() {
    global $db;

    $sql_name=$db->escapeString($this->name);
    $db->query("delete from directory_content where directory_id='{$this->directory_id}' and name='{$sql_name}'");
    // Special delete for virtual table
    $db->query("delete from search_index where rowid=(select rowid from search_index where directory_id='{$this->directory_id}' and name='{$sql_name}')");
  }

  function update() {
  }

  function db_create() {
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
