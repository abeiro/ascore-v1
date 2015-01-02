<?php

class wInlineImage extends wObject implements wRenderizable {

    var $name = "";
    var $label = "Image";
    var $src = "";
    var $click = "";
    var $id;
	var $cssclass="icon";
	var $showLoader=false;
	var $base64data="";
	var $maxWidth="";
	var $maxHeight="";

    function __construct($name=null, $parent, $addPrefix=true) {
        parent::__construct($name, $parent);
		$this->name=$name;
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
        echo "
		<input type='hidden' name='{$this->name}' id='{$this->id}'  class='{$this->cssclass}' value='{$this->value}' title='{$this->label}' onchange='document.getElementById(\"{$this->id}_viewer\").src=this.value' />
		<img  style='{$this->cssStyle}' $eventCode name='{$this->name}_viewer' src='{$this->value}' id='{$this->id}_viewer'  class='{$this->cssclass}'  title='{$this->label}' />
		<input type='file' onchange='UploadInlineimage(\"{$this->id}_uploader\",\"{$this->id}\")' name='{$this->name}_uploader' src='{$this->value}' id='{$this->id}_uploader'  class='{$this->cssclass}'   />
	";
	
		
    }

    /* Some default properties */

	

    function _setDefaults() {

        $this->setCSS("display", "inline-block");
    }

    function setSelectedValue($data) {
        $this->value = $data;
    }

}

?>