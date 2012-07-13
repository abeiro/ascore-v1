<?php

class wTextArea extends wObject implements wRenderizable {

  
  
  var $name="";
  var $value="";
  
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
    echo "
      <div style='position:relative'>
      <textarea name='{$this->name}' $eventCode id='{$this->id}' style='{$this->cssStyle}'>{$this->value}</textarea>\n
      <img src='{$SYS["ROOT"]}/Framework/Extensions/wGui/Img/editor.png' onclick='openTextHelper($(\"{$this->id}\"),this)' style='position:absolute;top:1px;right:1px;cursor:pointer'></div>
      ";
      
  }
  
  /* Some default properties */
  function _setDefaults() {
    
    $this->setCSS("width","200px");
    $this->setCSS("resize","none");
    
  }
  
  function setSelectedValue($data) {
    $this->value=$data;
  }
  
}

?>