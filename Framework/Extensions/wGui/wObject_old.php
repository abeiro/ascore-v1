<?php


class wObject {

	var $wChildren=array();			// Array of Children
	var $wParent=null;				// Parent
	var $Listener=array();			// Array of Listeners
	var $ListenerAux=array();		// Array of Listeners (aux)
	var $id;						// Internal ID
	var $cssStyle='';				// String storing static css styles
	var $staticCSS=array();			// Array of static css styles
	
	function __construct($name=null,&$parent=null) {
		global $xajax;
		if ($name)
			$this->id=$name;
		else
			$this->id=md5(get_class($parent).get_class($this));
			
		$this->addListener("onclick","actionperformed",$this);
		if ($parent) {
			$this->wParent=&$parent;
			$parent->add($this);
		}
		
		if (method_exists($this,"_setDefaults")) {
			$this->_setDefaults();
		}
		
		if (method_exists($this,"Init")) {
			$this->Init();
		}
		
		$this->wParent=&$parent;
		
	
	}
	
	function setCSS($value,$data) {
	
		$this->staticCSS[$value]=$data;
		$this->cssStyle="";
		foreach ($this->staticCSS as $k=>$v)
			$this->cssStyle.="$k:$v;";
	}
	function add(&$object) {
		
		$this->wChildren[]=&$object;
		$object->wParent=&$this;
	
	}
	
	function addListener($event,$function,$object=null) {
		global $xajax;
		if ($object==null) {
			$this->Listener[$event]=$xajax->register(XAJAX_FUNCTION,$function);
			$listenerPointer=&$this->Listener[$event];
			}
		else {
			$this->Listener[$event]=$xajax->register(XAJAX_CALLABLE_OBJECT,$object);
			$this->ListenerAux[$event]=strtolower($function);
			$listenerPointer=&$this->Listener[$event][$this->ListenerAux[$event]];
			$this->Listener[$event]=&$this->Listener[$event][$this->ListenerAux[$event]];
		}
		
		$listenerPointer->setParameter(0,XAJAX_QUOTED_VALUE,$this->id);
		$listenerPointer->setParameter(1,XAJAX_QUOTED_VALUE,$event);
	
	}
	
	
	
	public static function actionPerformed($id,$event) {
	
		debug("actionperformed :$id $event");
	
	}
	
}


?>
