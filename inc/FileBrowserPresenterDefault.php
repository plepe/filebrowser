<?php
class FileBrowserPresenterDefault extends FileBrowserPresenter {
  function __construct($file) {
    parent::__construct($file);
  }

  function render_fileview() {
    return "<a href='".$this->file->download_url()."'>Download</a>\n";
  }
}

