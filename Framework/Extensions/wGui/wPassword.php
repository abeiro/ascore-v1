<?php

class wPassword extends wInput implements wRenderizable {

    var $editMode=false;

    function render() {

        global $SYS;
        
        foreach ($this->Listener as $k => $v) {
            if (!is_array($v))
                $eventCode.=" $k='" . $v->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            else {
                $eventCode.=" $k='" . $v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            }
        }

	if ($this->editMode==true) {
	  echo <<<JSCODE
<script>
function chgpass(a,b,c) {
  debugger;
  a=$(a);
  b=$(b);
  c=$(c);
  if (a.value.length==0) {
    alert("Empty password");
    return;
  } else {
    if (a.value!=b.value) {
      alert("Passwords doesn't match");
      return;
    } else {
      c.value=md5(a.value);
      $('{$this->id}').style.display="none";
      alert('Save register now');
    }
  }
}
</script>
JSCODE;
	  echo "<div id='{$this->id}' style='{$this->cssStyle}'><input type='password' name='{$this->name}' $eventCode id='{$this->id}_p1' 
            value='{$this->value}'  maxlength='{$this->maxlenght}' placeholder='{$this->placeholder}' 
                >\n";
	  echo "<input type='password' name='{$this->name}2' $eventCode id='{$this->id}_p2' 
            value=''  maxlength='{$this->maxlenght}' placeholder='{$this->placeholder}' 
                >\n";
	  echo "<input type='hidden' name='password' id='{$this->id}_real' 
            value='{$this->value}'>\n";
	  echo "<input type='button' style='padding:3px' value='"._("Change")."' onclick=\"chgpass('{$this->id}_p1','{$this->id}_p2','{$this->id}_real')\">\n";
	  echo "</div>";
	} else {
	  echo "<input type='password' name='{$this->name}' $eventCode id='{$this->id}' 
            value='{$this->value}' style='{$this->cssStyle}' maxlength='{$this->maxlenght}' placeholder='{$this->placeholder}' 
                onkeypress='checkKeyPresssEnter(event,\"{$this->id}\")'>\n";

	}
    }

}

?>