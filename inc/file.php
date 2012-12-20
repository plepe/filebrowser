<?
class _file extends _item {
  function __construct($path_part, $parent, $data=null) {
    parent::__construct($parent);
    global $db;

    $this->path_part=$path_part;

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

  function type() {
    return "file";
  }
}
