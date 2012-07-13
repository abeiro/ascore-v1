<?php

require_once("Bilo.php");
HTML("action_header");


$u=newObject("user_pref");
$u->setAll($_POST);
$u->user_id=BILO_uid();
if ($u->save()) {
	echo _("Guardado");
	$u->setPrefs();
	jsAction("alert('Vuelva a entrar en la aplicación para que los cambios tengan efecto. Pulsar F5 puede ayudarle a conseguirlo.')");
	
}
else {
	echo _("Error");
	
}


?>