<?php

class wImage extends wObject implements wRenderizable {

    var $name = "";
    var $label = "Button";
    var $src = "";
    var $click = "";
    var $id;
	var $cssclass="icon";
	var $showLoader=false;
	var $tag="input";

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
		if ($this->tag=="input")
			echo "<input type='image' style='{$this->cssStyle}' $eventCode name='{$this->name}' id='{$this->id}'  class='{$this->cssclass}' src='{$this->src}' title='{$this->label}' />";
		else if ($this->tag=="a") {
			echo "<a  style='{$this->cssStyle}' $eventCode name='{$this->name}' id='{$this->id}'  class='{$this->cssclass}' title='{$this->label}'><img src='{$this->src}' title='{$this->label}' /></a>";
		}
	
    }

    /* Some default properties */

    function _setDefaults() {

        
    }

    function setSelectedValue($data) {
        $this->value = $data;
    }

}

?>