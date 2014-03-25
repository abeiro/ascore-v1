<?php

class wFileUpload extends wObject implements wRenderizable {

    var $name = "";
    var $value = "";
    var $maxlenght = "";
    var $tipo = "";

    function __construct($name=null, &$parent) {
        parent::__construct($name, $parent);
        $this->name = $name;
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
		$functionName=strtr($this->id,array("."=>"_"));

        echo "<div id='file_{$this->id}' style='{$this->cssStyle}'></div>
	<script>        
        function {$functionName}_createUploader(){            
            var uploader = new qq.FileUploader({
                element: document.getElementById('file_{$this->id}'),
                action: '{$SYS["ROOT"]}/Framework/Extensions/FileUpload2/upload.php?sourceid=${$this->id}&type={$this->tipo}',
		onComplete: function(id, fileName, responseJSON){
				document.getElementById('{$this->id}').value=responseJSON.newId;
				document.getElementById('{$this->id}').simulate('change');
				alert('Guarde el formulario para hacer efectivo el cambio');
				//document.getElementById('file_{$this->id}').style.display='none';
				}

            });           
        }
        
	EventHandlers[cEventHandlers]={$functionName}_createUploader;
	cEventHandlers++;
        </script>
	<input type='hidden' name='{$this->name}' $eventCode name='{$this->name}' id='{$this->id}' value='{$this->value}'>
	\n";
    }

    //window.onload = createUploader;     
    /* Some default properties */
    function _setDefaults() {
        
    }

    function setSelectedValue($data) {
        $this->value = $data;
    }

}

?>