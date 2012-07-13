<?php

require_once("Bilo.php");
plantHTML(array(),"f_menu");
setLimitRows(50);

$r=newObject("registro");
if (empty($sort))
	$sort="dia DESC ";
$r->searchResults=$r->selectAll($offset,$sort);

listList($r,array("usuario"=>"xref#user|user_id|username"),"registros");

resetLimitRows();

HTML("footer");



