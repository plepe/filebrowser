<?
class _archive extends _directory {
  function __construct($archive_id) {
    global $db;
    global $paths;

    $this->archive_id=$archive_id;
    $this->path_part=$archive_id;
    $this->parent=get_root();

    if(!isset($paths[$archive_id]))
      throw new Exception("Archive '{$archive_id}' not found!");

    $this->data=$paths[$archive_id];

    $res=$db->query("select * from directory where path='/' and archive_id='{$this->archive_id}'");
    if(!($data=$res->fetchArray()))
      throw new Exception("Root of archive '{$archive_id}' not found!");

    $this->directory_id=$data['directory_id'];
  }

  function url($options=array()) {
    $ret="";

    if(isset($options['_file']))
      $ret.=$options['_file'];

    $ret.="?p={$this->archive_id}";

    return $ret;
  }

  function name() {
    return $this->data['name'];
  }

  function path() {
    return "/";
  }

  function type() {
    return "archive";
  }
}
