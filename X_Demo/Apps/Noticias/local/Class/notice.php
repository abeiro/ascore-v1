<?php
function save() {

 	$f=newObject("fileh");
	$f->CaptureFile='foto';

	
	//dataDump($this);
	$par=new Ente($this->name);
	$par=typecast($this,"Ente");
	//dataDump($par);
	return $par->save();

}
?>