<?php

require_once("GlobalSche/GlobalSche.php");
HTML("action_header");

$libro=newObject("gtasklog",$ID);
if ($libro->delete()) {

  echo _("Borrado correctamente");
  frameGo("fbody","tasklog_list.php");
}
else
  echo _("No borrado");



?>