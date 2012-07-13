<?php

class wMutableLabel extends wObject implements wRenderizable {

    var $name = "";
    var $value = "";

    function __construct($name=null, &$parent, $label) {
        parent::__construct($name, $parent);
        $this->name = $name;
        $this->value = $label;
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
        echo "<input readonly type='text' name='{$this->name}' $eventCode name='{$this->name}' id='{$this->id}' value='{$this->value}' style='{$this->cssStyle}'>\n";
    }

    /* Some default properties */

    function _setDefaults() {

        $this->setCSS("width", "99%");
        $this->setCSS("border", "0px");
        $this->setCSS("background-color", "transparent");
    }

    function setSelectedValue($data) {
        $this->value = $data;
    }

}

?>