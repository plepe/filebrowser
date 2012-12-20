<?
class _file extends _item {
  function __construct($path_part, $parent, $data=null) {
    parent::__construct($path_part, $parent);
    global $db;

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
    $this->archive=$this->parent->archive;
  }

  function path() {
    return $this->parent->path().$this->path_part;
  }

  function name() {
    return $this->data['name'];
  }

  function type() {
    return "file";
  }

  function get_contents() {
    return $this->archive->get_contents($this->path());
  }

  function fopen($mode) {
    return $this->archive->fopen($this->path(), $mode);
  }

  function file_stat() {
    return $this->archive->file_stat($this->path());
  }

  function print_content() {
    return "<a href='".$this->url(array("_file"=>"download.php"))."'>Download</a>\n";
  }
}
