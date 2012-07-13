<?php
require_once("Biblio.php");
HTML("action_header");

if($ID>1)
{
	$u=newObject("cat_lib",$ID);
	if ($u->delete())
		echo "Borrado correctamente";

}else
	echo "ERROR";
	
FrameGo("fbody","list_catlib.php");


?>