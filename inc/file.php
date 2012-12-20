<?
class _file {
  function __construct($path_part, $parent, $data=null) {
    global $db;

    $this->path_part=$path_part;
    $this->parent=$parent;

    if($data===null) {
      $sql_name=$db->escapeString($this->path_part);
      $parent_id=$this->parent->directory_id;

      $res=$db->query("select d.directory_id, d.path, f.name from file f join directory d on f.directory_id=d.directory_id where name='{$sql_name}' and d.directory_id={$parent_id}");

      if(!($data=$res->fetchArray()))
        throw new Exception("File '{$this->path_part}' not found");
    }

    $this->directory_id=$data['directory_id'];
    $this->name=$data['name'];
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

    $ret.="<li class='file'>".$this->print_link()."</li>\n";

    return $ret;
  }
}
