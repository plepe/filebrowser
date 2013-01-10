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
    $this->archive=$this;
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

  function item_path() {
    return $this->archive_id;
  }

  function path() {
    return "/";
  }

  function type() {
    return "archive";
  }

  function get_contents($path) {
    return file_get_contents($this->data['path']."/$path");
  }

  function get_directory_content($path) {
    $ret=array();

    $d=opendir($this->data['path']."/{$path}");
    while($f=readdir($d)) {
      if(substr($f, 0, 1)!=".")
        $ret[]=$f;
    }
    closedir($d);

    return $ret;
  }

  function fopen($path, $mode) {
    return fopen($this->data['path']."/$path", $mode);
  }

  function file_stat($path) {
    $stat=stat($this->data['path']."/$path");
    if(!$stat)
      return null;

    $stat['mime_type']=mime_content_type($this->data['path']."/$path");

    return $stat;
  }
}
