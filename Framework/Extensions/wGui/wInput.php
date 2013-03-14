<?php

class wInput extends wObject implements wRenderizable {

	
	
	var $name="";
	var $value="";
	var $maxlenght="";
	var $legend;
	var $autocomplete=false;
	function __construct($name=null,&$parent,$addPrefix=true) {
		parent::__construct($name,$parent);
		$this->name=$name;
		if (($addPrefix))
			$this->id="{$parent->id}.{$this->id}";
		
	
	}
	function render() {

		global $SYS;
                parent::render();
		foreach ($this->Listener as $k=>$v) {
			if (!is_array($v))
				$eventCode.=" $k='".$v->getScript($SYS["ROOT"]."/Framework/Extensions/xajax")."'";
			else {
				$eventCode.=" $k='".$v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"]."/Framework/Extensions/xajax")."'";
				}
			
		} if ($this->autocomplete) {
			echo "{$this->legend}<input type='text' name='{$this->name}' $eventCode id='{$this->id}' value='{$this->value}' style='{$this->cssStyle}' maxlength='{$this->maxlenght}'>\n";
		
			echo "<div name='{$this->name}_autosg' id='{$this->id}_autosg' value='' style='position:fixed;background-color:white;width:{$this->staticCSS["width"]};z-index:2;'></div>\n";
			
		} else  {
			echo "{$this->legend}<input type='text' name='{$this->name}' $eventCode id='{$this->id}' value='{$this->value}' style='{$this->cssStyle}' maxlength='{$this->maxlenght}'>\n";
		}
	}
	
	/* Some default properties */
	function _setDefaults() {
		
		$this->setCSS("width","200px");
			if ($this->autocomplete)
				$this->setCSS("position","relative");
		
	}
	
	function setSelectedValue($data) {
		$this->value=$data;
	}
	
}

?>