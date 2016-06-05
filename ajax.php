<?php include "conf.php"; /* load a local configuration */ ?>
<?php include "modulekit/loader.php"; /* loads all php-includes */ ?>
<?php
session_start();

function ajax_set_view($param) {
  $_SESSION['mode']=$param['mode'];

  return true;
}

_ajax_process();
