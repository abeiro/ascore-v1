<?php

class wLabel extends wObject implements wRenderizable {

    var $label = '';

    function __construct($name=null, $parent, $label, $addPrefix=true) {
        parent::__construct($name, $parent);
        $this->label = $label;
        if (($addPrefix))
            $this->id = "{$parent->id}.{$this->id}";
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
        echo "<span $eventCode id='{$this->id}' style='{$this->cssStyle}'>\n\t{$this->label}\n</span>";
    }

    /* Some default properties */

    function _setDefaults() {

        $this->setCSS("width", "100%");
    }

}

?>