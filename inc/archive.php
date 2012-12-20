<?
class _archive extends _directory {
  function __construct($archive_id) {
    global $db;
    global $paths;

    $this->archive_id=$archive_id;

    if(!isset($paths[$archive_id]))
      throw new Exception("Archive '{$archive_id}' not found!");

    $this->data=$paths[$archive_id];

    $res=$db->query("select * from directory where path='/' and archive_id='{$this->archive_id}'");
    if(!($data=$res->fetchArray()))
      throw new Exception("Root of archive '{$archive_id}' not found!");

    $this->directory_id=$data['directory_id'];
  }

  function url() {
    return "?p={$this->archive_id}";
  }

  function path() {
    return "/";
  }
}
