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

  function content() {
    global $db;
    if(isset($this->content))
      return $this->content;

    $res=$db->query("select name, 'directory' as type from directory_link where directory_id='{$this->directory_id}' union select name, 'file' as type from file where directory_id='{$this->directory_id}'");
    while($data=$res->fetchArray()) {
      switch($data['type']) {
        case 'directory':
          $this->content[]=new _directory($data['name'], $this);
          break;
        case 'file':
          $this->content[]=new _file($data['name'], $this);
          break;
      }
    }

    return $this->content;
  }

  function type() {
    return "directory";
  }
}
