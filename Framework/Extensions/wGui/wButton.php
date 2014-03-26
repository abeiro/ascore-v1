<?php

class wButton extends wObject implements wRenderizable {

    var $name = "";
    var $label = "Button";
    var $disabled = false;
    var $type = "button";
    var $tooltip = "";
    var $warning = "";

    function __construct($name = null, &$parent, $label = "Button") {
        parent::__construct($name, $parent);
        $this->name = $name;
        $this->label = $label;
    }

    function render() {
        global $SYS;
        parent::render();
        $disabledlabel = ($this->disabled) ? "disabled readonly" : "";
        foreach ($this->Listener as $k => $v) {
            if (($k == "onclick") && ($this->warning)) {
                if (!is_array($v))
                    $eventCode.="  $k='if (confirm(\"{$this->warning}\"))" . $v->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
                else {
                    $eventCode.=" $k='if (confirm(\"{$this->warning}\")) " . $v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
                }
            }
            if (!is_array($v))
                $eventCode.=" $k='" . $v->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            else {
                $eventCode.=" $k='" . $v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            }
        }
        echo "<input type='{$this->type}' $disabledlabel $eventCode name='{$this->name}' id='{$this->id}' value='{$this->label}' size='" . (strlen($this->label) * 5) . "' title='{$this->tooltip}' style='{$this->cssStyle}'>\n";
    }

    /* Some default properties */

    function _setDefaults() {

        $this->setCSS("width", (strlen($this->label) * 12) . "px");
        $this->setCSS("padding-top", "2px");
        $this->setCSS("margin", "4px");
        $this->setCSS("padding-bottom", "2px");
    }

    function setSelectedValue($data) {
        $this->value = $data;
    }

    function setDisabled($bool) {
        $this->disabled = $bool;
    }

	function setIcon($iconurl) {
        $this->setCSS("width", ((strlen($this->label) * 12)) . "px");
        $this->setCSS("padding-left", "10px");
		
        
        $this->setCSS("background-image","url(\"$iconurl\")");
		$this->setCSS("background-position","2px center");
		$this->setCSS("background-repeat","no-repeat");
	
    }

}

?>