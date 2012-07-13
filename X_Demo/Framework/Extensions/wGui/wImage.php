<?php

class wImage extends wObject implements wRenderizable {

    var $name = "";
    var $label = "Button";
    var $src = "";
    var $click = "";
    var $id;

    function __construct($name=null, $parent, $addPrefix=true) {
        parent::__construct($name, $parent);

        if (($addPrefix))
            $this->id = "{$parent->id}.{$this->id}";
    }

    function render() {
        global $SYS;
        parent::render();
        foreach ($this->Listener as $k => $v) {
            if (!is_array($v))
                $eventCode.=" $k='{$this->preEvents[$k]}" . $v->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            else {
                $eventCode.=" $k='" . $v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            }
        }
        echo "<input type='image' $eventCode name='{$this->name}' id='{$this->id}' value='{$this->label}' class='icon' src='{$this->src}' title='{$this->label}' onClick='{$this->click}' />\n";
    }

    /* Some default properties */

    function _setDefaults() {

        $this->setCSS("width", (strlen($this->label) * 12) . "px");
    }

    function setSelectedValue($data) {
        $this->value = $data;
    }

}

?>