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
    return array();
  }

  function url() {
    return "?search=".urlencode($this->search_text);
  }
}

function get_search($text) {
  return new _search($text);
}
