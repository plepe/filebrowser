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

  function content($options=array()) {
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

    // check if directory still exists
    $stat=$this->archive->file_stat($this->data['path']);
    // no -> check all sub directories
    if(!$stat) {
      $res=$db->query("select * from directory_content where directory_id='{$this->directory_id}' and sub_directory is not null");
      while($data=$res->fetchArray())
        get_directory($data['sub_directory'])->update();

      foreach($this->directory_content() as $item)
        $item->db_remove();
      $this->db_remove();
      $db->query("delete from directory where directory_id='{$this->directory_id}'");

      return;
    }

    $actual_content=$this->archive->get_directory_content($this->data['path']);

    $res=$db->query("select * from directory_content where directory_id='{$this->directory_id}'");
    while($data=$res->fetchArray()) {
      $name=$data['name'];
      $db_content[$name]=$data;

      if(!in_array($name, $actual_content)) {
        // print "Gone: {$name}\n";

        if($data['sub_directory'])
          get_directory($data['sub_directory'])->update();
        $sql_name=$db->escapeString($name);
        get_item($this->item_path()."/{$name}")->db_remove();
      }
    }

    foreach($actual_content as $name) {
      if(!isset($db_content[$name])) {
        // print "New: {$name}\n";

        $sql_name=$db->escapeString($name);

        $stat=$this->archive->file_stat("{$this->data['path']}/{$name}");
        if($stat['mime_type']=="directory")
          $new_item=new _directory($name, $this, $stat);
        else
          $new_item=new _file($name, $this, $stat);

        $new_item->db_create();
      }
    }

    // print_r($actual_content);
    // print_r($db_content);

    // Invalidate cache
    unset($this->content);
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
