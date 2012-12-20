<?
class _item {
  function __construct($path_part, $parent) {
    $this->parent=$parent;
    $this->path_part=$path_part;
  }

  function path() {
    return $this->parent->path().$this->path_part."/";
  }

  function url() {
    return $this->parent->url()."/".urlencode($this->path_part);
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
}
