<?php

/* Extensión de la clase grupo */

function donothing() {
	return True;

}


function generate_skel() {

	$i=1;
	
	$LEVEL=2;
	$MAX_LEVEL=31;
	for ($i=2;$i<=31;$i++) {
	
		$code=pow($LEVEL,$i-1);
		$o=newObject("group",$i);
		$o->code=$code;
		if (empty($o->active))
			$o->active='No';
		$o->save();
	
	
	}


}
function listGroups() {

	return $this->select("active='Si'");

}

function listGroupIndex() {

	$arr=$this->select("active='Si'");
	$ob=current($arr);
	for ($i=0,$j=sizeof($arr);$i<$j;$i++) {
		$gindex[$ob->ID]=$ob->groupname;
		$ob=next($arr);
	}
	return $gindex;
}

function getGroupByName($codename='nogroup') {

	$res=$this->select("groupname='$codename'");
	$data=current($res);
	$this->setAll($data->properties);
	

}

function listUsersInGroup() {

	$o=newObject("user");
	return ($o->select("(grupos&{$this->code}={$this->code})"));

}

function listUsersInGroupId() {

	$o=newObject("user");
	$res=$o->select("(grupos&{$this->code}={$this->code})");
	foreach ($res as $k=>$v)
		$r[$v->ID]=$v->apellidos.",  ".$v->nombre;
	return $r;

}

function nextIDFree() {

	$arr=$this->select("active='No'");
	if ($arr) {
		$res=current($arr);
		return $res->ID;
	}
	return 0;

}
?>