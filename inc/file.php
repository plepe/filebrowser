<?
class _file extends _item {
  function __construct($path_part, $parent, $data=null) {
    parent::__construct($path_part, $parent);
    global $db;

    if($data===null) {
      $sql_name=$db->escapeString($this->path_part);
      $parent_id=$this->parent->directory_id;

      $res=$db->query("select d.directory_id, d.path, f.name from directory_content f join directory d on f.directory_id=d.directory_id where name='{$sql_name}' and d.directory_id={$parent_id} and f.sub_directory is null");

      if(!($data=$res->fetchArray()))
        throw new Exception("File '{$this->path_part}' not found");
    }

    if(!isset($data['path']))
      $data['path']=$this->parent->data['path'];
    if(!isset($data['directory_id']))
      $data['directory_id']=$this->parent->directory_id;
    if(!isset($data['name']))
      $data['name']=$path_part;

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

  function thumbnail_url($options=array()) {
    $options['_file']="thumbnail.php/{$this->name()}";

    return $this->url($options);
  }

  function get_thumbnail() {
    return $this->presenter()->get_thumbnail();
  }

  function stat_thumbnail() {
    return $this->presenter()->stat_thumbnail();
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

  function print_thumbnail($options=array()) {
    return $this->presenter()->render_thumbnail($options);
  }

  function db_create() {
    global $db;

    $sqlite_file=$db->escapeString($this->name);
    $db->query("insert into directory_content (directory_id, name) values ({$this->directory_id}, '{$sqlite_file}')");
    $db->query("insert into search_index (directory_id, name) values ({$this->directory_id}, '{$sqlite_file}')");
  }
}
