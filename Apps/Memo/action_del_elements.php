<?php
require_once("Memo.php");
require_once("security.php");

HTML("action_header");
	

$e2del=explode(",",$IDS);

foreach ($e2del as $k=>$v) {
	if (!empty($v)) {
		$o=newobject("data_object",$v);
		$death_name=$o->nombre;
		if (checkSecurity($o)) {
			if ($o->delete())
				echo _("{$o->nombre} : elemento borrado ").$o->WARNING;
			else
				echo _("{$o->nombre} : elemento no borrado").$o->ERROR;
			frameReload("fbody");
		}
		else
			echo _("No tiene premisos para esta acción.");
	}

}


HTML("action_footer");


?>