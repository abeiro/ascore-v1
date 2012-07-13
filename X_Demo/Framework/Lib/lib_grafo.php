<?php

class grafoSimple {

	
	
	var $vertices;
	
	var $aristas;
	
	var $current;
	
	function grafoSimple() {
	
		$this->vertices=array();
		$this->aristas=array();
		
		return True;
	
	}
	
	function addVertex($name) {
	
		if (in_array($name,$this->vertices))
			return False;
		
		$this->vertices[]=$name;
		
		return True;
	
	
	}
	
	function addEdge($orig,$dest,$name) {
	
		foreach ($this->aristas as $k=>$v)
			if (in_array($name,$v))
				if (($v["origen"]==$orig)&&($v["destino"]==$dest))
					return False;
		
		$this->aristas[]=array(
				"nombre"=>$name,
				"origen"=>$orig,
				"destino"=>$dest
				);
		return true;
	}
	
	function findWay($A,$B) {
		
		foreach ($this->aristas as $k=>$v)
			if (($v["origen"]==$A)&&($v["destino"]==$B))
				return $v["nombre"];
		return False;
	}
	
	function findWayStrict($A,$B,$name) {
		
		foreach ($this->aristas as $k=>$v)
			if (($v["origen"]==$A)&&($v["destino"]==$B)&&($v["nombre"]==$name))
				return True;
		return False;
	}
	
	function findPoint($edge) {
		
		foreach ($this->aristas as $k=>$v)
			if (($v["nombre"]==$edge))
				return $v["destino"];
		return False;
	}
	
	function findPWay($point) {
		
		foreach ($this->aristas as $k=>$v)
			if (($v["origen"]==$point))
				return $v["nombre"];
		return False;
	}
	

}
?>
