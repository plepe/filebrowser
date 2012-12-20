<?
class _directory {
  function __construct($path_part, $parent, $data=null) {
    global $db;

    $this->path_part=$path_part;
    $this->parent=$parent;

    if($data===null) {
      $sql_path=$db->escapeString($this->path());
      $parent_id=$this->parent->directory_id;

      $res=$db->query("select d.directory_id, d.path, dl.name from directory d join directory_link dl on d.directory_id=dl.sub_directory where d.path='{$sql_path}' and dl.directory_id={$parent_id}");

      if(!($data=$res->fetchArray()))
        throw new Exception("Directory '{$this->path_part}' not found");
    }

    $this->directory_id=$data['directory_id'];
    $this->data=$data;
  }

  function name() {
    return $this->data['name'];
  }

  function path() {
    return $this->parent->path().$this->path_part."/";
  }

  function url() {
    return $this->parent->url()."/".urlencode($this->path_part);
  }

  function print_link() {
    return "<a href='".$this->url()."'>".
      htmlspecialchars($this->data['name']).
      "</a>";
  }

  function print_link_path() {
    $ret="";

    if($this->parent)
      $ret.=$this->parent->print_link_path();

    $ret.="<li class='directory'>".$this->print_link()."</li>\n";

    return $ret;
  }
}
