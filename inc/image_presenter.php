<?
class image_presenter extends _presenter {
  function render_fileview() {
    return "<a href='".$this->file->download_url()."'>".
      "<img src='".$this->file->download_url()."'/>".
      "</a>\n";
  }
}

register_presenter("image_presenter", "~^image/(png|jpeg|gif)$~");
