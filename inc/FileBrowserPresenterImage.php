<?php
class FileBrowserPresenterImage extends FileBrowserPresenter {
  function render_fileview() {
    return "<a href='".$this->file->download_url()."'>".
      "<img src='".$this->file->download_url()."'/>".
      "</a>\n";
  }

  function render_thumbnail($options=array()) {
    return "<img src='".$this->file->thumbnail_url()."'/>";
  }

  function get_thumbnail() {
    global $cache;

    $file_name="{$cache}/{$this->file->directory_id}-{$this->file->name}";

    if(file_exists($file_name))
      return file_get_contents($file_name);

    $f=$this->file->fopen("r");
    $p=popen("convert -resize 128x128\> - 'JPG:{$file_name}'", "w");
    while($r=fgets($f))
      fwrite($p, $r);

    fclose($f);
    pclose($p);

    return file_get_contents($file_name);
  }

  function stat_thumbnail() {
    return array("mime_type"=>"image/jpeg");
  }
}

register_presenter("FileBrowserPresenterImage", "~^image/(png|jpeg|gif)$~");
