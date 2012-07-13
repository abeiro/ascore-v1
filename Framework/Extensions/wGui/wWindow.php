<?php

class wWindow extends wObject implements wRenderizable {

	var $name="";
	var $title="";
	var $type=0;
	

function __construct($name=null,&$parent=null,$addPrefix=true) {
		parent::__construct($name,$parent);
		$this->name=$name;
		if (($addPrefix))
			$this->id="{$parent->id}.{$this->id}";
		
		define("WINDOW.NORMAL",0);
		define("WINDOW.FIXED",1);
		
	
	}
	
	function render() {
                parent::render();
		echo "<div class='wWindowCss'  id='{$this->id}' style='{$this->cssStyle}'>\n";
		if ($this->type==WINDOW.NORMAL) {
			echo "<div class='wWindowCssTitle' id='{$this->id}_title' onclick='wGuiWindowToUpperLayer(this)'>{$this->title} 
	
					<span class='wWindowCssButtomMin' id='{$this->id}_min' onclick='wGuiWindowMinimize(this)'>X</span>
					
					<span class='wWindowCssButtomMax' id='{$this->id}_max' onclick='wGuiWindowMaximize(this)'>+</span>
					</div>
					
					<div  id='{$this->id}_content' class='wWindowCssContent'>
					";
			
			foreach ($this->wChildren as $c) 
				$c->render();
				
			echo "</div></div>";
			
			
			echo "<script>new Draggable('{$this->id}', {handle: '{$this->id}_title',scroll: window })</script>";
		}  else
		if ($this->type==WINDOW.FIXED) {
			echo "<div class='wWindowCssTitle' id='{$this->id}_title' >{$this->title} 
	
					
					</div>
					
					<div  id='{$this->id}_content' class='wWindowCssContent'>
					";
			
			foreach ($this->wChildren as $c) 
				$c->render();
				
			echo "</div></div>";
		}
}
	
	function _setDefaults() {
		/* Some default properties */

		$this->setCSS("position","absolute");
		$this->setCSS("display","block");
		
		
	}
	
	
}


?>