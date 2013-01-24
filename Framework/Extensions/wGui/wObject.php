<?php

class wObject implements wRenderizable {

    var $wChildren = array();   // Array of Children
    var $wParent = null;    // Parent
    var $Listener = array();   // Array of Listeners
    var $ListenerAux = array();  // Array of Listeners (aux)
    var $id;      // Internal ID
    var $cssStyle = '';    // String storing static css styles
    var $staticCSS = array();   // Array of static css styles
    var $__internalid;
    var $className;

    function __construct($name = null, &$parent = null) {
        global $xajax;
        if ($name)
            $this->id = $name;
        else
            $this->id = md5(get_class($parent) . get_class($this));




        if (method_exists($this, "_setDefaults")) {
            $this->_setDefaults();
        }

        if (method_exists($this, "Init")) {
            $this->Init();
        }

        $this->wParent = &$parent;

        if (!$GLOBALS["objhash"]) {
            $GLOBALS["objhash"][0] = &$this;
            $this->__internalid = 0;
        } else {
            $n = sizeof($GLOBALS["objhash"]) + 1;
            $GLOBALS["objhash"][$n] = &$this;
            $this->__internalid = $n;
        }

        if ($parent) {
            $this->wParent = &$parent;
            $parent->add($this);
        }
        debug("Obj $name {$this->__internalid}", "blue");
        try {
            $this->className = $this->getMyClassName();
        } catch (Exception $idontcare) {
            
        }
        //$this->addListener("onclick","actionperformed",$this);
    }

    function setCSS($value, $data) {

        $this->staticCSS[$value] = $data;
        $this->cssStyle = "";
        foreach ($this->staticCSS as $k => $v)
            $this->cssStyle.="$k:$v;";
    }

    function setStyle($csstring) {
        $css = explode(";", $csstring);
        foreach ($css as $singlecss) {
            $pairs = explode(":", trim($singlecss));
            $this->staticCSS[$pairs[0]] = $pairs[1];
        }
        foreach ($this->staticCSS as $k => $v)
            $this->cssStyle.="$k:$v;";
    }

    function add(&$object) {

        $this->wChildren[$object->__internalid] = &$object;
        $object->wParent = &$this;
    }

    function addListener($event, $function, $object = null) {
        global $xajax;
        if ($object == null) {
            $this->Listener[$event] = pseudoListener::register(XAJAX_FUNCTION, $function);
            $listenerPointer = &$this->Listener[$event];
        } else {
            $this->Listener[$event] = pseudoListener::register(XAJAX_CALLABLE_OBJECT, $function, $object);
            $this->ListenerAux[$event] = strtolower($function);
            $listenerPointer = &$this->Listener[$event];
            $this->Listener[$event]->realListener = &$this->Listener[$event]->realListener[$this->ListenerAux[$event]];
        }

        $listenerPointer->setParameter(0, XAJAX_QUOTED_VALUE, $this->id);
        $listenerPointer->setParameter(1, XAJAX_QUOTED_VALUE, $event);

        return $this->Listener[$event];
    }

    public static function actionPerformed($id, $event) {

        debug("actionperformed :$id $event");
    }

    public function render() {
        
    }

    public function renderS() {
        ob_start();
        $this->render();
        $code = ob_get_contents();
        ob_end_clean();
        return $code;
    }

    public function getMyClassName() {
        return get_class($this);
    }

}

class pseudoListener {

    var $realListener;
    var $sourceData;

    public static function register($a, $b, &$class = null) {
        global $xajax;

        $t = new pseudoListener();

        if ($a == XAJAX_CALLABLE_OBJECT) {
            $t->realListener = $xajax->register($a, $class);
            $t->sourceData["objid"] = $class->__internalid;
            $t->sourceData["objclass"] = get_class($class);
        }
        else
            $t->realListener = $xajax->register($a, $b);

        $t->sourceData["type"] = $a;
        $t->sourceData["function"] = ("$b");
        /* if ($a==XAJAX_CALLABLE_OBJECT) 
          debug("Parameters $a,$b: ".get_class($class). " # ".print_r($t->realListener[strtolower($b)],true),"red"); */


        return $t;
    }

    function setParameter($a, $b, $c) {

        $this->sourceData["parameters"][$a] = array($b, $c);

        return $this->realListener->setParameter($a, $b, $c);
    }

    function addParameter($a, $b) {
        $this->sourceData["parameters"][] = array($a, $b);
        return $this->realListener->addParameter($a, $b);
    }

    function getScript() {
        return $this->realListener->getScript();
    }

}

?>
