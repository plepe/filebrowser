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

  function presenter() {
    if(isset($this->presenter))
      return $this->presenter;

    $stat=$this->file_stat();

    $this->presenter=get_presenter($this);

    return $this->presenter;
  }

  function print_info() {
    $ret="";
    $stat=$this->file_stat();

    $ret ="<ul>\n";
    $ret.="<li>Mime-Type: {$stat['mime_type']}</li>\n";
    $ret.="<li>Filesize: {$stat['size']}</li>\n";
    $ret.="<li>Creation: ".format_date($stat['ctime'])."</li>\n";
    $ret.="<li>Mod. Date: ".format_date($stat['mtime'])."</li>\n";
    $ret.="</ul>\n";

    // $ret.="<pre>".print_r($stat, 1)."</pre>\n";

    return $ret;
  }

  function print_content() {
    return $this->presenter()->render_fileview();
  }
}
