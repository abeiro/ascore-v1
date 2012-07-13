<?php

class wButton extends wObject implements wRenderizable {

    var $name = "";
    var $label = "Button";

    function __construct($name=null, &$parent, $label="Button") {
        parent::__construct($name, $parent);
        $this->name = $name;
        $this->label = $label;
    }

    function render() {
        global $SYS;
        parent::render();
        foreach ($this->Listener as $k => $v) {
            if (!is_array($v))
                $eventCode.=" $k='" . $v->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            else {
                $eventCode.=" $k='" . $v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            }
        }
        echo "<input type='button' $eventCode name='{$this->name}' id='{$this->id}' value='{$this->label}' size='" . (strlen($this->label) * 5) . "' style='{$this->cssStyle}'>\n";
    }

    /* Some default properties */

    function _setDefaults() {

        $this->setCSS("width", (strlen($this->label) * 12) . "px");
        $this->setCSS("padding-top", "2px");
        $this->setCSS("margin", "2px");
        $this->setCSS("padding-bottom", "2px");
    }

    function setSelectedValue($data) {
        $this->value = $data;
    }

}

?>