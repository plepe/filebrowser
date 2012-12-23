<?
$presenter_types=array();

class _presenter {
  function __construct($file) {
    $this->file=$file;
  }

  function render_fileview() {
  }

  function render_thumbnail() {
  }
}

class default_presenter extends _presenter {
  function __construct($file) {
    parent::__construct($file);
  }

  function render_fileview() {
    return "<a href='".$this->file->url(array("_file"=>"download.php"))."'>Download</a>\n";
  }
}

function register_presenter($class, $mime_regexp) {
  global $presenter_types;

  $presenter_types[]=array(
    'class'=>   $class,
    'regexp'=>  $mime_regexp,
  );
}

function get_presenter($file) {
  global $presenter_types;
  $stat=$file->file_stat();
  $ret=null;

  foreach($presenter_types as $presenter_type) {
    if(preg_match($presenter_type['regexp'], $stat['mime_type'], $m)) {
      $class=$presenter_type['class'];

      $ret=new $class($file, $m);
    }
  }

  if(!$ret)
    $ret=new default_presenter($file);

  return $ret;
}
