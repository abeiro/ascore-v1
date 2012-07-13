<?php
function MdP(&$res,$link="",$max=0) {

	
	if ($max>4)
		return true;
		
	$p=newObject("departamento",$this->cat_id,$max++);
	
	
		
	if ($p->ID<2) {
		$res.="-&#062;<a href=\"$link?cat_id={$this->ID}\">{$this->nombre}</a>";
		return true;
	}
	else {
		
		$p->MdP(&$res,$link,$max);
		$res.="-&#062;<a href=\"$link?cat_id={$this->ID}\">{$this->nombre}</a>";
	}
	
	return true;
}

function PublicMdP(&$res,$link="",$max=0) {

	
	if ($max>4)
		return true;
		
	$p=newObject("departamento",$this->cat_id,$max++);
	
	
		
	if ($p->ID<2) {
		$res.=" -&#062; <a class=\"minimal\" href=\"$link/ID={$this->ID}/\">{$this->nombre}</a>";
		return true;
	}
	else {
		
		$p->PublicMdP(&$res,$link,$max);
		$res.="-&#062;<a class=\"minimal\" href=\"$link/ID={$this->ID}/\">{$this->nombre}</a>";
	}
	
	return true;
}


?>