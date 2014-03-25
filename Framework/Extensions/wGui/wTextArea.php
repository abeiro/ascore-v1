<?php

class wTextArea extends wObject implements wRenderizable {

  
  
  var $name="";
  var $value="";
  var $maxbutton="true";
  var $advancedEditor=false;
  var $advancedEditorAutoLoad=false;
  var $placeholder="";
  var $status="";
 
  function __construct($name=null,&$parent,$addPrefix=true) {
    parent::__construct($name,$parent);
    $this->name=$name;
    if (($addPrefix))
      $this->id="{$parent->id}.{$this->id}";
  
  }
  function render() {
    global $SYS;
    parent::render();
    foreach ($this->Listener as $k=>$v) {
      if (!is_array($v))
        $eventCode.=" $k='".$v->getScript($SYS["ROOT"]."/Framework/Extensions/xajax")."'";
      else {
        $eventCode.=" $k='".$v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"]."/Framework/Extensions/xajax")."'";
        }
      
    }
	if (!$this->advancedEditor) {
		echo "
		<div style='position:relative'>
                <textarea name='{$this->name}' {$this->status} $eventCode id='{$this->id}' style='{$this->cssStyle}' title='{$this->tooltip}' tooltip='{$this->tooltip}'  placeholder='{$this->placeholder}'>{$this->value}</textarea>\n";
		if ($this->maxbutton=="true")
			echo "
				<img src='{$SYS["ROOT"]}/Framework/Extensions/wGui/Img/editor.png' onclick='openTextHelper($(\"{$this->id}\"),this)' style='position:absolute;top:1px;right:1px;cursor:pointer'></div>
		";
		else
			echo "</div>";
	} else {
		echo "<textarea class='adv_{$this->name}' {$this->status} name='{$this->name}' $eventCode id='{$this->id}' style='{$this->cssStyle}'>{$this->value}</textarea>\n";
		echo "<script src='{$SYS["ROOT"]}/Framework/Extensions//ckeditor/ckeditor.js'></script>";
		jsAction("$('{$this->id}').loadEditor=
		function() {try{CKEDITOR.replace(\"{$this->name}\",
			{
			toolbar: 'Basic',
			allowedContent: 'a[!href];ul; li{text-align};strong;p{text-align};h2{text-align}'
			})
		} catch (idontcare){}}
		
		");	
		if ($this->advancedEditorAutoLoad) {
			jsAction("$('{$this->id}').loadEditor()");
		}
		

	}

  }
  
  /* Some default properties */
  function _setDefaults() {
    
    $this->setCSS("width","200px");
    $this->setCSS("resize","none");
    
  }
  
  function setSelectedValue($data) {
    $this->value=$data;
  }

	function setMaxbutton($yn) {
    $this->maxbutton=$yn;
  }
  
}

?>