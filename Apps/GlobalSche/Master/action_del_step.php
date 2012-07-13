<?php
  
  require_once("GlobalSche/GlobalSche.php");
  HTML("action_header");
  
  $libro=newObject("gstep",$ID);
  if ($libro->delete()) {
    
    echo _("Borrado correctamente");
    frameGo("fbody","step_list.php");
  }
  else
    echo _("No borrado");
  
  
  
  ?>