<?php

class wListBox extends wObject implements wRenderizable {

    var $name = "";
    var $value = "";
    var $dataModel = array();
    var $moreHTMLproperties = "";
    var $readonly = false;

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
        if (!$this->readonly) {
            echo "<select {$this->moreHTMLproperties}  name='{$this->name}' $eventCode id='{$this->id}'  style='{$this->cssStyle}'>\n";
            foreach ($this->dataModel as $k => $v) {
                $addSel = ($this->value == $k) ? "selected" : "";
                echo "\t<option value='$k' label='$v' $addSel>$v</option>\n";
            }
            echo "</select>\n";
        } else {
            echo "<select {$this->moreHTMLproperties}  disabled   id='faked_{$this->id}'  style='{$this->cssStyle}'>\n";
            foreach ($this->dataModel as $k => $v) {
                $addSel = ($this->value == $k) ? "selected" : "";
                echo "\t<option value='$k' label='$v' $addSel>$v</option>\n";
            }
            echo "</select>\n";

            echo $this->dataModel["$this->value"] . " <input type='hidden' id='{$this->id}' name='{$this->name}' style='background-color:gray' onchange='\$(\"faked_{$this->id}\").value=this.value'  value='{$this->value}'>";
        }
    }

    /* Some default properties */

    function _setDefaults() {

        $this->setCSS("width", "200px");
        $this->setCSS("min-height", "14px");
    }

    function setDataModel($data) {
        $this->dataModel = $data;
    }

    function setSelectedIndex($i) {
        $this->value = $i;
    }

    function setReadOnly() {
        $this->readonly = true;
    }

}

?>
