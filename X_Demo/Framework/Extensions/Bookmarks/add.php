<?php
require_once("coreg2.php");
HTML("action_header");

$url=urldecode($params);
$b=newObject("bookmark");
$b->url=$url;
$b->nombre=$name;
$b->user_id=BILO_uid();

if (!$b->save()) {
	echo $b->ERROR;
}
else
	echo "Guardado correctamente [$name]";

HTML("action_footer");
?>