<?php

class wImage extends wObject implements wRenderizable {

    var $name = "";
    var $label = "Button";
    var $src = "";
    var $click = "";
    var $id;
	var $showLoader=false;

    function __construct($name=null, $parent, $addPrefix=true) {
        parent::__construct($name, $parent);

        if (($addPrefix))
            $this->id = "{$parent->id}.{$this->id}";
    }

    function render() {
        global $SYS;
        parent::render();
        debug(">>>>>>>>>>>>>>>>>>>>> this image <{$this->name}> <{$this->id}> has  ".sizeof($this->Listener ). " listeners" );
        foreach ($this->Listener as $k => $v) {
            if (!is_array($v))
                $eventCode.=" $k='{$this->preEvents[$k]}" . $v->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            else {
                $eventCode.=" $k='" . $v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            }
        }
        echo "<input type='image' $eventCode style='{$this->cssStyle}' name='{$this->name}' id='{$this->id}'  class='icon' src='{$this->src}' title='{$this->label}' />";
	
    }

    /* Some default properties */

    function _setDefaults() {

        
    }

    function setSelectedValue($data) {
        $this->value = $data;
    }

}

?>