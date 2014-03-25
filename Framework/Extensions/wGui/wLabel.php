<?php

class wLabel extends wObject implements wRenderizable {

    var $label = '';
	var $tooltip='';

    function __construct($name=null, $parent, $label, $addPrefix=true) {
        parent::__construct($name, $parent);
        $this->label = $label;
        if (($addPrefix))
            $this->id = "{$parent->id}.{$this->id}";
    }

    function render() {
        global $SYS;
        parent::render();
		if ($this->tooltip) {
			$this->setCSS("background","transparent url(\"{$SYS["ROOT"]}/Framework/Extensions/wGui/Img/tooltip.png\") no-repeat");
			$this->setCSS("background-position","top right");
			$this->setCSS("padding-right","15px");		
		}

        foreach ($this->Listener as $k => $v) {
            if (!is_array($v))
                $eventCode.=" $k='" . $v->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            else {
                $eventCode.=" $k='" . $v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            }
        }
        echo "<label $eventCode id='{$this->id}' title='{$this->tooltip}' style='{$this->cssStyle}'>\n\t{$this->label}\n</label>";
    }

    /* Some default properties */

    function _setDefaults() {
		
        $this->setCSS("display", "inline-block");
		$this->setCSS("padding-right", "5px");
    }

}

?>