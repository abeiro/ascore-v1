<?php

class wCheckBox extends wObject implements wRenderizable {

    var $name = "";
    var $value = "";

    function __construct($name=null, &$parent, $addPrefix=true) {
        parent::__construct($name, $parent);
        $this->name = $name;
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
        //value='{$this->value}'
        $addSel = ($this->value) ? "checked" : "";
        echo "<input type='checkbox' name='{$this->name}' $eventCode id='{$this->id}'  style='{$this->cssStyle}' $addSel>\n";
    }

    /* Some default properties */

    function _setDefaults() {
        
    }

    function setDataModel($data) {
        $this->dataModel = $data;
    }

    function setChecked($i) {
        $this->value = $i;
    }

    // Stub function. Deprecated
    function setSelectedIndex($i) {
        trigger_error("Deprecated function called." . __FILE__ . " " . __FUNCTION__ . " " . __CLASS__, E_USER_NOTICE);
        $this->value = $i;
    }

}

?>
