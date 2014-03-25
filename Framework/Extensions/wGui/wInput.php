<?php

class wInput extends wObject implements wRenderizable {

	
	
	var $name="";
	var $value="";
	var $maxlength="";
	var $placeholder="";
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

		if ($this->readonly) {
			$this->readonly="readonly";
		}

		if ($this->tooltip) {
			$this->setCSS("background","{$this->staticCSS["background-color"]} url(\"{$SYS["ROOT"]}/Framework/Extensions/wGui/Img/tooltip.png\") no-repeat");
			$this->setCSS("background-position","top right");
			$this->setCSS("padding-right","15px");		
		}

		foreach ($this->Listener as $k=>$v) {
			if (!is_array($v))
				$eventCode.=" $k='".$v->getScript($SYS["ROOT"]."/Framework/Extensions/xajax")."'";
			else {
				$eventCode.=" $k='".$v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"]."/Framework/Extensions/xajax")."'";
				}
			
		} if ($this->autocomplete) {
			echo "{$this->legend}<input type='text' name='{$this->name}' $eventCode id='{$this->id}' value='{$this->value}' style='{$this->cssStyle}' maxlength='{$this->maxlenght}' placeholder='{$this->placeholder}' title='{$this->tooltip}'>\n";
		
			echo "<div name='{$this->name}_autosg' id='{$this->id}_autosg' value='' style='position:fixed;background-color:white;width:{$this->staticCSS["width"]};z-index:5;'></div>\n";
			
		} else  {
			echo "{$this->legend}<input type='text' name='{$this->name}' $eventCode id='{$this->id}' value='{$this->value}' style='{$this->cssStyle}' {$this->readonly} maxlength='{$this->maxlenght}' title='{$this->tooltip}' placeholder='{$this->placeholder}'>\n";
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



class wInputSearchable extends wObject implements wRenderizable {

	
	
	var $name="";
	var $value="";
	var $maxlength="";
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
                               if ($k!="ondelayedchange")
				$eventCode.=" $k='".$v->getScript($SYS["ROOT"]."/Framework/Extensions/xajax")."'";
			else 
                            if ($k!="ondelayedchange")
                                	$eventCode.=" $k='".$v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"]."/Framework/Extensions/xajax")."'";
		}
			
        $customEventCode=$this->Listener["ondelayedchange"]->getScript($SYS["ROOT"]."/Framework/Extensions/xajax");
        $sid="{$this->id}_searchbox";
		$hid="{$this->id}";
  
		echo "<span class='elementContainer'>{$this->legend}
				<input type='text' onkeydown='checkKeyPressTabIS(event)' onkeyup='delayedKeyUp($(\"{$this->id}_searchbox\"),\"{$this->id}\")'
				name='{$this->name}_searchbox' id='{$this->id}_searchbox' 
				value='{$this->textvalue}' style='{$this->cssStyle}' maxlength='{$this->maxlenght}' onblur='setTimeout(function() { $(\"{$this->id}_autosg\").update(\"\") },200)'>\n";
			echo "<img onclick='$(\"$sid\").value=\"\";$(\"$hid\").value=\"\";Event.simulate(\"$hid\",\"change\")' style='left:-10px;cursor:pointer;' src='{$GLOBALS["SYS"]["ROOT"]}Framework/Extensions/wGui/Img/mini_del.gif' 
			title='"._("Limpiar")."'/>";
			
			echo "{$this->legend}<input type='hidden' 
                            name='{$this->name}' $eventCode id='{$this->id}' 
                                value='{$this->value}' style='{$this->cssStyle}' maxlength='{$this->maxlenght}'>\n";
		
			echo "<div name='{$this->name}_autosg' class='autodropdown' id='{$this->id}_autosg' value='' style='position:absolute;background-color:white;width:{$this->staticCSS["width"]};z-index:5;'></div>\n";
                        echo "<script>$('{$this->id}').addEventListener('delayedchange',function() { $customEventCode })</script></span>";
		
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