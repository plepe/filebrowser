<?php
class FileBrowserSearch extends FileBrowserItem {
  function __construct($parent, $text) {
    $this->search_text=$text;

    $this->parent = $parent;
    $this->root = $parent->root;
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
      $directory = $this->root->get_directory($data['directory_id']);
      switch($data['type']) {
        case 'directory':
          $this->content[] = new FileBrowserDirectory($directory, $data['name']);
          break;
        case 'file':
          $this->content[] = new FileBrowserFile($directory, $data['name']);
          break;
      }
    }

    return $this->content;
  }

  function url() {
    return "?search=".urlencode($this->search_text);
  }
}
