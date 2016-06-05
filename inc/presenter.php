<?php
$presenter_types=array();

class _presenter {
  function __construct($file) {
    $this->file=$file;
  }

  function render_fileview() {
  }

  function render_thumbnail($options=array()) {
    return "<img src='".$this->thumbnail_url($options)."' />";
  }

  function thumbnail_url() {
    return "lib/tango/scalable/mimetypes/text-x-generic.svg";
  }

  function get_thumbnail() {
    return file_get_contents($this->thumbnail_url($options));
  }

  function stat_thumbnail() {
    return array("mime_type"=>"image/svg+xml");
  }
}

class default_presenter extends _presenter {
  function __construct($file) {
    parent::__construct($file);
  }

  function render_fileview() {
    return "<a href='".$this->file->download_url()."'>Download</a>\n";
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
