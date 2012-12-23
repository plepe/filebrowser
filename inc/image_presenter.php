<?
class image_presenter extends _presenter {
  function render_fileview() {
    return "<a href='".$this->file->url(array("_file"=>"download.php"))."'>".
      "<img src='".$this->file->url(array("_file"=>"download.php"))."'/>".
      "</a>\n";
  }
}

register_presenter("image_presenter", "~^image/(png|jpeg|gif)$~");
