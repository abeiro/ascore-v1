<?php

function getPrefByUser($user_id) {

	$res=$this->select("user_id=$user_id");
	
	if ($this->nRes>0) {
		$data=current($res);
		$this->setAll($data->properties);
		$this->ID=$data->properties["ID"];	
	}

}

function setPrefs() {
	
	global $SYS;

	foreach ($this->properties as $k=>$v) {
			$_SESSION["GLOBAL"]["$k"]=$v;
			$SYS["GLOBAL"]["$k"]=$v;
	}
	/* Dual mode viewing */
	$SYS["GLOBAL"]["sys_app_mode"]=($SYS["GLOBAL"]["sys_app_mode"]!='No')?true:false;
	

}

?>