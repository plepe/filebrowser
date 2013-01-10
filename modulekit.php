<?php
$name="Filebrowser";

// an ID to identify this module
$id="filebrowser";

// these modules should be loaded first
$depend=array("ajax");

// these files will be included in this order:
$include=array();
$include['php']=array(
  "inc/item.php",
  "inc/directory.php",
  "inc/presenter.php",
  "inc/*.php",
);
$include['js']=array(
  "inc/*.js",
);
$include['css']=array(
  "inc/*.css",
);
