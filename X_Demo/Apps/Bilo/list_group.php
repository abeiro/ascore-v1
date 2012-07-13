<?php

require_once("Bilo.php");

if(BILO_isOperator()){



	$g=newObject("group");
	$u=newObject("user");
	$g->isAdmin=BILO_isAdmin();
	setLimitRows(30);
	$g->searchResults=$g->select("active='Si'",$offset,$sort);
	listList($g,array(),"list_group");
	resetLimitRows();
}else
	echo "<h4 align=\"center\">PRIVILEGIOS INSUFICIENTES</h4>";
	

?>


