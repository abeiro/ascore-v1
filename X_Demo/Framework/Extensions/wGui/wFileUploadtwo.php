<?php

class wFileUploadtwo extends wObject implements wRenderizable {

    var $name = "";
    var $value = "";
    var $maxlenght = "";

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
        echo "<script language='javascript' src='../../../Framework/Extensions/FileUpload2/js/jquery-1.3.1.min.js'></script>
<script language='javascript' src='../../../Framework/Extensions/FileUpload2/js/AjaxUpload.2.0.min.js'></script>
<script language='javascript'>
$(document).ready(function(){
	var button = $('#upload_button'), interval;
	new AjaxUpload('#upload_button', {
        action: '../../../Framework/Extensions/FileUpload2/upload.php?sourceid=${$this->id}',
		onSubmit : function(file , ext){
		if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
			
			alert('Error: Solo se permiten imagenes');
			
			return false;
		} else {
			button.text('Uploading');
			this.disable();
		}
		},
		onComplete: function(file, response){
			button.text('Adjuntar imagen');
			
			this.enable();			
			
			$('#lista').appendTo('.files').text(file);
		}	
	});
});
</script>
<link href='../../../Framework/Extensions/FileUpload2/style.css' rel='stylesheet' type='text/css' />
<div id='upload_button'>Adjuntar imagen</div>
<ul id='lista'>
</ul> 
<input type='hidden' name='{$this->name}' $eventCode name='{$this->name}' id='{$this->id}' value='{$this->value}'>
	\n";
    }

    /* Some default properties */

    function _setDefaults() {

        $this->setCSS("width", "200px");
    }

    function setSelectedValue($data) {
        $this->value = $data;
    }

}

?>