<?php
class FileBrowserSearch extends FileBrowserItem {
  function __construct($text) {
    $this->search_text=$text;

    $this->parent=file_browser_get_root();
  }

  function name() {
    return "Search '{$this->search_text}'";
  }

  function type() {
    return "search";
  }

  function content() {
    global $db;
    if(isset($this->content))
      return $this->content;

    $sql_search=$db->escapeString($this->search_text);

    $res=$db->query("select directory_content.*, case when sub_directory is null then 'file' else 'directory' end as type from search_index left join directory_content on search_index.directory_id=directory_content.directory_id and search_index.name=directory_content.name where search_index.name match '{$sql_search}'");
    while($data=$res->fetchArray()) {
      $directory=file_browser_get_directory($data['directory_id']);
      switch($data['type']) {
        case 'directory':
          $this->content[]=new FileBrowserDirectory($data['name'], $directory);
          break;
        case 'file':
          $this->content[]=new FileBrowserFile($data['name'], $directory);
          break;
      }
    }

    return $this->content;
  }

  function url() {
    return "?search=".urlencode($this->search_text);
  }
}

function file_browser_get_search($text) {
  return new FileBrowserSearch($text);
}
