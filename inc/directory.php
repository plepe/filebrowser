<?
class _directory extends _item {
  function __construct($path_part, $parent, $data=null) {
    parent::__construct($path_part, $parent);
    global $db;

    if($data===null) {
      $sql_name=$db->escapeString($this->path_part);
      $parent_id=$this->parent->directory_id;

      $res=$db->query("select d.directory_id, d.path, dl.name from directory d join directory_link dl on d.directory_id=dl.sub_directory where dl.name='{$sql_name}' and dl.directory_id={$parent_id}");

      if(!($data=$res->fetchArray()))
        throw new Exception("Directory '{$this->path_part}' not found");
    }

    $this->directory_id=$data['directory_id'];
    $this->data=$data;
  }

  function name() {
    return $this->data['name'];
  }

  function type() {
    return "directory";
  }
}
