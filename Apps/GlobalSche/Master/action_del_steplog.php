<?php

require_once("GlobalSche/GlobalSche.php");
HTML("action_header");

$libro=newObject("gsteplog",$ID);
if ($libro->delete()) {

  echo _("Borrado correctamente");
  frameGo("fbody","steplog_list.php");
}
else
  echo _("No borrado");



?>