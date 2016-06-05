<?php
$name="Filebrowser";

// an ID to identify this module
$id="filebrowser";

// these modules should be loaded first
$depend=array("ajax");

// these files will be included in this order:
$include=array();
$include['php']=array(
  "inc/functions.php",
  "inc/FileBrowserItem.php",
  "inc/FileBrowser.php",
  "inc/FileBrowserDirectory.php",
  "inc/FileBrowserArchive.php",
  "inc/FileBrowserFile.php",
  "inc/FileBrowserPresenter.php",
  "inc/FileBrowserPresenterDefault.php",
  "inc/FileBrowserPresenterImage.php",
  "inc/FileBrowserSearch.php",
);
$include['js']=array(
  "inc/*.js",
);
$include['css']=array(
  "inc/*.css",
);
