<?php

class wHidden extends wObject implements wRenderizable {

    var $name = "";
    var $value = "";
    var $maxlenght = "";

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
        echo "<input type='hidden' name='{$this->name}' $eventCode name='{$this->name}' id='{$this->id}' value='{$this->value}'>\n";
    }

    function setSelectedValue($data) {
        $this->value = $data;
    }

	function setDataModel() {
        return;
    }

}

?>
