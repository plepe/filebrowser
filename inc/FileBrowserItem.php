<?php
class FileBrowserItem {
  function __construct($parent, $path_part) {
    $this->parent=$parent;
    $this->root = $parent->root;
    $this->path_part=$path_part;
  }

  function path() {
    return $this->parent->path().$this->path_part."/";
  }

  function item_path() {
    return $this->parent->item_path()."/".$this->path_part;
  }

  function get_index($item) {
    if(!method_exists($this, 'content'))
      return null;

    foreach($this->content() as $index => $content_item) {
      if($item->path_part == $content_item->path_part)
        return $index;
    }

    return null;
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

  function print_content($param=array(), $options=array()) {
    $ret="";
    if(!isset($param['start']))
      $param['start']=0;
    if(!isset($param['count']))
      $param['count']=60;

    $ret.="<table class='list'>\n";
    $content=$this->content($options);
    for($i=$param['start'];
      $i<min($param['start']+$param['count'], sizeof($content)); $i++) {
      $item=$content[$i];

      $ret.=$item->print_entry();
    }

    if(sizeof($content)>$param['start']+$param['count']) {
      $url=$this->url(array("start"=>$param['start']+$param['count']));
      $ret.="<tr id='placeholder'><td colspan='3'><a href='{$url}'>next page</a></td></tr>\n";
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
