<?php

/* Extensión de la clase inventarios_muestrarios */

function donothing() {
	return True;

}

function save() {

 	debug("Info: Calling Extended save");
	
	
		/* Tratamos el fichero */
	if ($this->type=="archive") {
		$fileh=newObject("fileh",$this->fileh);
		$fileh->CaptureFile="file_fichero";
		if (!empty($_FILES[$fileh->CaptureFile]["name"])) {
			
			debug("A files has been uploaded","red");
			$fileh->fecha=time();
			$fileh->desc=$this->descripcion;
			$fileh->nombre=$_FILES[$fileh->CaptureFile]["name"];
			$fileh->familia_id=_FILES;
			$filehandler=$fileh->save();	
			if ($filehandler!=0) 
				$this->fileh=$filehandler;
			$this->nombre=$fileh->nombre;
			$this->mime=$fileh->mime;
			$this->fecha=time();
		}
		else {
			$this->fecha=time();
		}
		
		
	}
	else {
			
		$this->fileh=0;
		$this->mime=$file->mime;
		$this->fecha=time();
	
	}

	//dataDump($this);
	$par=new Ente($this->name);
	$par=typecast($this,"Ente");
	//dataDump($par);
	return $par->save();

}
function save_copy($orig,$inode) {

 	debug("Info: Calling save_copy");
	
	
		/* Tratamos el fichero */
	$this->setAll($orig->properties);
	if ($this->type=="archive" ) {
		$fileh=newObject("fileh",1);
		$ofileh=newObject("fileh",$orig->fileh);
		$fileh->fecha=time();
		$fileh->desc=$ofileh->descripcion;
		$fileh->nombre=$ofileh->nombre;
		$fileh->familia_id=$ofileh->familia_id;
		$filehandler=$fileh->save($ofileh->localname());	
		if ($filehandler!=0) 
			$this->fileh=$filehandler;
		$this->nombre=$fileh->nombre;
		$this->mime=$fileh->mime;
		$this->fecha=time();
		$this->inode=$inode;
		}
	else {
		$this->ERROR="Cannot copy directories yet";
		return false;
	}
	
	$this->ID=1;
	//dataDump($this);
	$par=new Ente($this->name);
	$par=typecast($this,"Ente");
	//dataDump($par);
	return $par->save();

}
function delete() {

 	debug("Info: Calling Extended delete");
	if ($this->type=="archive") {
		$par=new Ente($this->name);
		$fi=newObject("fileh",$this->fileh);
		if (!$fi->delete()) 
			$this->WARNING=$fi->ERROR;
			
		$par=typecast($this,"Ente");
		return $par->delete();
	}
	else {
		/* Ohohohoho we've a dir here */
		$confirmation=newObject("data_object");
		$n=$confirmation->select("inode=".$this->ID);
		if ($confirmation->nRes<1) {
			/* Lets detelete */
			$par=new Ente($this->name);
			$par=typecast($this,"Ente");
			return $par->delete();
		}
		else {
			$this->ERROR=_("Folder not empty");
			return False;
		}
	
	}
}

function mdp($mdp_text) {

 	if (($this->inode==0)||(empty($this->inode))) {
		$mdp_text.=$this->nombre."\\";
		return;
	}
	else {
		$r=newObject("data_object",$this->inode);
		$r->mdp(&$mdp_text);
		$mdp_text.=$this->nombre."\\";
		return;
	}
}

?>
