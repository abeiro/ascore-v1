<?php

function save() {

	$obj=newObject($this->campos["entity"]);
	$this->usuario_id=BILO_uid();
	$this->vista=$this->campos['entity'];

	foreach($this->campos as $key=>$value) {
		if(!in_array($key,array_keys($obj->properties)))
			unset($this->campos[$key]);
	}

	$this->campos_ser=serialize($this->campos);
	setLimitRows(15000);
	$this->searchResults=$this->select("usuario_id={$this->usuario_id} AND vista='{$this->vista}'");
	resetLimitRows();

        if($this->nRes > 0)
		$this->ID=$this->searchResults[0]->ID;
	else
		$this->ID=1;

	$par = new Ente($this->name);
        $par = typecast($this, "Ente");

        return $par->save();
}

function loadView() {

	setLimitRows(15000);
	$this->searchResults=$this->select("usuario_id={$this->usuario_id} AND vista='{$this->vista}'");
	resetLimitRows();

	if($this->nRes > 0) {
		$this->ID=$this->searchResults[0]->ID;
		$visibility=unserialize($this->searchResults[0]->campos_ser);
		$script = "<script type=\"text/javascript\" language=\"JavaScript1.3\">\n";
		$script.= "function loadView(){\n";
		foreach($visibility as $key=>$value){
			if($value != 'none'){
				$value = "";
			}
			$script.= "\tvar x = document.getElementsByName('$key');\n";
			$script.= "\tfor ( i=0 ; i < x.length ; i++ ){\n";
			$script.= "\t\tx[i].style.display = '$value';\n";
			$script.= "\t}\n";
		}
		$script.= "}\n \tloadView();\n";
		$script.= "</script>\n";
	}
	echo $script;
}

?>