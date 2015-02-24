<?php

class wCheckBox extends wObject implements wRenderizable {

    var $name = "";
    var $value = "";
    var $nValue = "";
    var $label = "";
    var $reversed = false;

    function __construct($name = null, &$parent, $addPrefix = true) {
        parent::__construct($name, $parent);
        $this->name = $name;
        if (($addPrefix))
            $this->id = "{$parent->id}.{$this->id}";
    }

    function render() {
        global $SYS;

        if ($this->visible === false)
            return;
        if ($this->tooltip) {
            $specialCSS = "background:transparent url(\"{$SYS["ROOT"]}/Framework/Extensions/wGui/Img/tooltip.png\") no-repeat;background-position:top right;padding-right:15px";
        }
        parent::render();
        foreach ($this->Listener as $k => $v) {
            if ($k != "onclick") {
                if (!is_array($v))
                    $eventCode.=" $k='" . $v->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
                else
                    $eventCode.=" $k='" . $v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            }
        }

        $addSel = (($this->value) && ($this->value != "false")) ? "checked" : "";
        //$this->value=(strlen($this->value)>0)?"true":"false";
        if (!$this->reversed)
            echo "<span style='$specialCSS' title='{$this->tooltip}'>{$this->label} </span>
                <input type='checkbox' name='{$this->name}_ctl' $eventCode id='{$this->id}_ctl'  style='{$this->cssStyle}' $addSel onclick='$(\"{$this->id}\").value=($(\"{$this->id}_ctl\").checked)?\"{$this->nValue}\":false' {$this->disabled}> ";
        else
            echo "<input type='checkbox' name='{$this->name}_ctl' $eventCode id='{$this->id}_ctl'  style='{$this->cssStyle}' $addSel onclick='$(\"{$this->id}\").value=($(\"{$this->id}_ctl\").checked)?\"{$this->nValue}\":false' {$this->disabled}>
            <span style='$specialCSS' title='{$this->tooltip}'>{$this->label} </span>  ";
        echo "<input type='hidden' name='{$this->name}' id='{$this->id}' value='{$this->value}'>\n";
    }

    /* Some default properties */

    function _setDefaults() {
        
    }

    function setDataModel($data) {
        $this->dataModel = $data;
    }

    function setDisabled($data) {
        if ($data == true)
            $this->disabled = "disabled";
        else
            $this->disabled = "";
    }

    function setChecked($i) {
        $this->value = $i;
        $this->nValue = $i;
    }

    function setLabel($i) {
        $this->label = $i;
    }

    // Stub function. Deprecated
    function setSelectedIndex($i) {
        trigger_error("Deprecated function called." . __FILE__ . " " . __FUNCTION__ . " " . __CLASS__, E_USER_NOTICE);
        $this->value = $i;
    }

}

?>
