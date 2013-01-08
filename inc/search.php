<?
class _search extends _item {
  function __construct($text) {
    $this->search_text=$text;

    $this->parent=get_root();
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
      $directory=get_directory($data['directory_id']);
      switch($data['type']) {
        case 'directory':
          $this->content[]=new _directory($data['name'], $directory);
          break;
        case 'file':
          $this->content[]=new _file($data['name'], $directory);
          break;
      }
    }

    return $this->content;
  }

  function url() {
    return "?search=".urlencode($this->search_text);
  }
}

function get_search($text) {
  return new _search($text);
}
