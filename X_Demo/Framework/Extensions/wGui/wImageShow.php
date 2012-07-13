<?php

class wImageShow extends wObject implements wRenderizable {

    var $name = "";
    var $label = "Button";
    var $src = "";
    var $idimage = "";
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
                $eventCode.=" $k='" . $v->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            else {
                $eventCode.=" $k='" . $v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            }
        }


        echo "
		<a target='_blank' href='{$SYS["ROOT"]}/Apps/Arboleas/public_mostrar_foto.php?ID={$this->idimage}' id='{$this->id}.link'>
		<img id='{$this->id}' height='64' src='{$SYS["ROOT"]}/Apps/Arboleas/public_mostrar_foto.php?ID={$this->idimage}'>
		</a>\n";
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