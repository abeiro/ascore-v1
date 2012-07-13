<?php

/* Extensión de la clase fileh */

function donothing() {
	return True;

}

function save($force=false) {

	global $SYS,$FAMILYLABEL;

 	debug("Info: Calling Extended save");
	$par=new Ente($this->name);
	
	/* Tratamos el fichero */
	
	
	if ($force===false) {
		$haveone=False;		
		if (!empty($_FILES[$this->CaptureFile]["name"])) {
			$haveone=True;	
			debug("fileh: Fichero subido ".$_FILES[$this->CaptureFile]["name"],"red");
		}
		else {
			debug("fileh: No se especificó fichero a subir","red");
			return 0;
		}
			
	
		if ($this->ID>1) {
		/* El fichero debería de existir */
			if (file_exists($SYS["BASE"]."/Pool/".$FAMILYLABEL[$this->familia_id]."/".$this->md5.".".$this->ext))
				/* El fichero existe debería de ser borrado si tenemos preparado uno nuevo*/
				if ($haveone) {
					unlink($this->localname());
					debug("Borrando....".$this->localname(),"red");
					
	
				}
			else
				debug("fileh: Debería de existir ".$this->localname().". Revise el sistema. Continuando","red");
				
		}		
	
		debug("<font color='#E6A129'>fileh:  Tratamiento de fichero </font>");	
		/* Copiamos el fichero y establecemos propiedades */
		
		$this->md5=md5(time()+$_FILES[$this->CaptureFile]["size"]);
		$this->len=$_FILES[$this->CaptureFile]["size"];
		$name_part=explode(".",$_FILES[$this->CaptureFile]["name"]);
		$this->ext=$name_part[sizeof($name_part)-1];
		$this->mime=$_FILES[$this->CaptureFile]["type"];
	    
	        $this->date=time();
	
		if (copy($_FILES[$this->CaptureFile]["tmp_name"],
		$SYS["BASE"]."/Pool/".$FAMILYLABEL[$this->familia_id]."/".$this->md5.".".$this->ext))
		{
			$par=typecast($this,"Ente");
			debug("fileh: fichero almacenado correctamente:".$this->localname(),"red");
			return $par->save();
	
		}
		else
			return 0;
	}
	else {
	
		$this->md5=md5(time()."$force");
		$this->len=filesize($force);
		$name_part=explode(".",$force);
		$this->ext=$name_part[sizeof($name_part)-1];
		$this->mime=mime_content_type($force);
		$this->date=time();
		$secure_new_name=$SYS["BASE"]."/Pool/".$FAMILYLABEL[$this->familia_id]."/".$this->md5.".".$this->ext;
		if (file_exists($secure_new_name)) {
			sleep(1);
			$this->md5=md5("$force".time()."-1");
			$secure_new_name=$SYS["BASE"]."/Pool/".$FAMILYLABEL[$this->familia_id]."/".$this->md5.".".$this->ext;
			if (file_exists($secure_new_name))
				echo "File name Collision!!!!!!";
		}
		if (copy($force,
		$secure_new_name))
		{
			$par=typecast($this,"Ente");
			debug("fileh: fichero almacenado correctamente:".$this->localname(),"red");
			return $par->save();
	
		}
	
	}
	
}

function localname() {

	global $SYS,$FAMILYLABEL;

 	return ($SYS["BASE"]."/Pool/".$FAMILYLABEL[$this->familia_id]."/".$this->md5.".".$this->ext);
}

function uriname() {

	global $SYS,$FAMILYLABEL;

 	return ($SYS["ROOT"]."/Pool/".$FAMILYLABEL[$this->familia_id]."/".$this->md5.".".$this->ext);
}

function delete() {

	global $SYS,$FAMILYLABEL;

 	if ($this->ID<2)
		return True;
		
	debug("Info: Calling Extended delete");
	$par=new Ente($this->name);
   	$target=$this->localname();
	if (unlink($target)) 
		debug("fileh: fichero borrado :".$this->localname(),"red");
		
		$par=typecast($this,"Ente");
		return $par->delete();
	

}
function getRawData() {
	
	if ($this->len>0) {
		$raw_h=fopen($this->localname(),"rb");
		$raw=fread($raw_h,$this->len);
		fclose($raw_h);
	}
	else
		header("HTTP/1.0 404 Not Found");

	return $raw;
	
}


function bitch() {
	return str_replace("/",".",$this->mime);
}

?>
