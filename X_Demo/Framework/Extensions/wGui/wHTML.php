<?php

class wHTML extends wObject implements wRenderizable {

    var $content;
    var $name = "";

    function setHTML($HTML) {
        $this->content = $HTML;
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
        echo "<div $eventCode id='{$this->id}' style='{$this->cssStyle}'>\n\t{$this->content}\n</div>";
    }

    /* Some default properties */

    function _setDefaults() {

        $this->setCSS("width", "100%");
    }

}

?>