<?php

class wHTML extends wObject implements wRenderizable {

    var $content;
    var $name = "";

    function setHTML($HTML) {
        $this->content = $HTML;
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
        echo "<div $eventCode id='{$this->id}' style='{$this->cssStyle}'>\n\t{$this->content}\n</div>";
    }

    /* Some default properties */

    function _setDefaults() {

        $this->setCSS("width", "100%");
    }

}


class wHTMLComponent extends wObject implements wRenderizable {
	function setHTML($HTML) {
        $this->content = $HTML;
    }

    function render() {
        global $SYS;
        parent::render();
		
			
		if ($this->attributes["style"] ) {
			$this->setStyle($this->attributes["style"]);
			unset($this->attributes["style"]);
		}
		foreach ($this->attributes as $attname=>$attvalue) 
			$attstring[]="$attname='$attvalue'";
		if ($this->content=="br")
			echo "<{$this->content} style='{$this->cssStyle}' ".implode(" ",$attstring)." id='{$this->id}'/>";
		else {
			echo "<{$this->content} style='{$this->cssStyle}' ".implode(" ",$attstring)." id='{$this->id}'> {$this->htmlContent}";
			foreach ($this->wChildren as $k => $c) 
				$c->render();
			echo "</{$this->content}>\n";
		}
        
    }

	function setAtt($attname,$attvalue) {
		$this->attributes[$attname]=$attvalue;

	}
}
?>