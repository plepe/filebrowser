<?
class _directory extends _item {
  function __construct($path_part, $parent, $data=null) {
    parent::__construct($path_part, $parent);
    global $db;

    if($data===null) {
      $sql_name=$db->escapeString($this->path_part);
      $parent_id=$this->parent->directory_id;

      $res=$db->query("select d.directory_id, d.path, dl.name from directory d join directory_content dl on d.directory_id=dl.sub_directory where dl.name='{$sql_name}' and dl.directory_id={$parent_id} and dl.sub_directory is not null");

      if(!($data=$res->fetchArray()))
        throw new Exception("Directory '{$this->path_part}' not found");
    }

    $this->directory_id=$data['directory_id'];
    $this->data=$data;
    $this->archive=$this->parent->archive;
  }

  function name() {
    return $this->data['name'];
  }

  function content() {
    global $db;
    if(isset($this->content))
      return $this->content;

    $res=$db->query("select name, case when sub_directory is null then 'file' else 'directory' end as type from directory_content where directory_id='{$this->directory_id}'");
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

  function update() {
    global $db;

    if($this==$this->archive)
      $actual_content=$this->get_directory_content("");
    else
      $actual_content=$this->archive->get_directory_content($this->data['path']);

    $res=$db->query("select * from directory_content where directory_id='{$this->directory_id}'");
    while($data=$res->fetchArray()) {
      $name=$data['name'];
      $db_content[$name]=$data;

      if(!isset($actual_content[$name])) {
        // print "Gone: {$name}\n";

        $sql_name=$db->escapeString($name);
        $db->query("delete from directory_content where directory_id='{$this->directory_id}' and name='{$sql_name}'");
      }
    }

    foreach($actual_content as $name=>$stat) {
      if(!isset($db_content[$name])) {
        // print "New: {$name}\n";

        $sql_name=$db->escapeString($name);
        $db->query("insert into directory_content (directory_id, name) values ('{$this->directory_id}', '{$sql_name}')");
      }
    }

    // print_r($actual_content);
    // print_r($db_content);
  }

  function type() {
    return "directory";
  }
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

  return get_item("{$data['archive_id']}{$data['path']}");
}
