<?php
function listGroupIndex() {

	$arr=$this->selectAll();
	$ob=current($arr);
	for ($i=0,$j=sizeof($arr);$i<$j;$i++) {
		$gindex[$ob->ID]=$ob->nombre;
		$ob=next($arr);
	}
	return $gindex;
}
?>