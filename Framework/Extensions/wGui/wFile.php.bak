<?php

class wFile extends wObject implements wRenderizable {

	
	
	var $name="";
	var $value="";
	var $maxlenght="";
	function __construct($name=null,&$parent) {
		parent::__construct($name,$parent);
		$this->name=$name;
	
	}
	function render() {
		global $SYS;
	
		foreach ($this->Listener as $k=>$v) {
			if (!is_array($v))
				$eventCode.=" $k='".$v->getScript($SYS["ROOT"]."/Framework/Extensions/xajax")."'";
			else {
				$eventCode.=" $k='".$v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"]."/Framework/Extensions/xajax")."'";
				}
			
		}
		echo "<iframe id='file_{$this->id}'  name='file_{$this->id}' height='22' border='0' style='border:0px;margin:0px;padding:0px;'>
			
			</iframe>
			<script>
			file_{$this->id}.document.write(\"<style>* {border:0px;margin:0px;padding:0px;}</style><form action='{$SYS["ROOT"]}' id='file_{$this->id}'><input type='file' name='{$this->name}' onchange='file_{$this->id}.submit()' target='_blank' id='{$this->id}' style='{$this->cssStyle}' maxlength='{$this->maxlenght}'></form>\");
			</script>
			\n";
	}
	
	/* Some default properties */
	function _setDefaults() {
		
		$this->setCSS("width","200px");
		
	}
	
	function setSelectedValue($data) {
		$this->value=$data;
	}
	
}

?>