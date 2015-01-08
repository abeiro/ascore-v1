<?php

function save() {
    debug("Info: Calling Extended save registro.php");
    $o=newObject("user",$this->user_id);
	$o->lastlog=time();
	
	$o->save();

    $par = new Ente($this->name);
    $par = typecast($this, "Ente");
    //dataDump($par);
    return $par->save();
}


?>
