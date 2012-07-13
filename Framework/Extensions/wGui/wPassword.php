<?php

class wPassword extends wInput implements wRenderizable {

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
        echo "<input type='password' name='{$this->name}' $eventCode id='{$this->id}' value='{$this->value}' style='{$this->cssStyle}' maxlength='{$this->maxlenght}'>\n";
    }

}

?>