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
            $this->id = md5(get_class($parent) . get_class($this) . (++$GLOBALS["__sequencer"]));




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
        //debug("Obj $name {$this->__internalid}", "blue");
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
        $this->cssStyle = "";
        foreach ($this->staticCSS as $k => $v)
            $this->cssStyle.="$k:$v;";
    }

    function add(&$object) {

        $this->wChildren[$object->__internalid] = &$object;
        $object->wParent = &$this;
    }

    function replace(&$object, $index) {

        $this->wChildren[$index] = &$object;
        $object->wParent = &$this;
    }

    function replaceSelf(&$object) {
		$object->wParent=$this->wParent;
        $this->wParent->wChildren[$this->__internalid] = &$object;
		
    }

    function getChildElementByName($name) {
        $possible = null;

        foreach ($this->wChildren as $idx => $child) {
            debug("Checking {$child->name}", "blue");
            if ($child->name == $name) {

                return $this->wChildren[$idx];
            } else {

                if (sizeof($child->wChildren) > 0) {

                    $possible = $child->getChildElementByName($name);
                    if ($possible != null)
                        return $possible;
                }
            }
        }
        return null;
    }

    function getChildElementByID($DOM_ID) {
        $possible = null;
        debug("Checking {$this->id} {$this->name} ", "green");
        foreach ($this->wChildren as $idx => $child) {
            debug("Checking {$this->id} {$this->name}: {$child->id} {$child->name} ", "blue");
            if ($child->id == $DOM_ID) {

                return $this->wChildren[$idx];
            } else {

                if (sizeof($child->wChildren) > 0) {
                    debug("Checking into {$child->id} {$child->name}", "blue");
                    $possible = $child->getChildElementByID($DOM_ID);
                    if ($possible != null)
                        return $possible;
                }
            }
        }
        return null;
    }

	function autoSetDataModel($field, $object, &$control) {
		
        if (strpos($object->properties_type[$field], "list") !== false) {
            $options = trim(substr($object->properties_type[$field], strpos($object->properties_type[$field], ":") + 1));
            if (method_exists($object, $options)) {
                $control->setSelectedValue($object->$field);
                $control->setDataModel($object->$options());
            } else {
                $ops = explode("|", $options);
                $o = null;
                if ($object->properties_properties[$field]["mandatory"])
                    $o["-"] = "-";
                foreach ($ops as $minikey => $minival)
                    $o["$minival"] = $minival;
                $control->setSelectedValue($object->$field);
                $control->setDataModel($o);
            }
        } else if (strpos($object->properties_type[$field], "ref") !== false) {
			$options = trim(substr($object->properties_type[$field], strpos($object->properties_type[$field], ":") + 1));
            $references = $object->get_references($field);
			if (sizeof($references)>5000) {		// too Big
				$control->setSelectedValue($object->$field);
				$control->setDataModel("{$object->name}|$field|$options");
				debug("Dynamic Data Model for {$object->name}|$field|$options","red");
				//die("{$object->name}|$field|$options");
				unset($references);
			} else {
				$control->setSelectedValue($object->$field);
				$control->setDataModel($references);
			}
        }
		
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

    public function renderAsPseudoCode() {

        $ret = "<{$this->className} id='{$this->id}' style='{$this->cssStyle}' value='{$this->value}' name='{$this->name}' label='{$this->label}'";
        if (sizeof($this->wChildren) > 0) {
            $ret.=">\n";
            foreach ($this->wChildren as $nodeName => $node)
                $ret.=$node->renderAsPseudoCode();
            $ret.="</{$this->className}>";
        } else {
            $ret.="/>\n";
        }

        return $ret;
    }

    function wObjectWalk($wnode, &$lastParent, &$parentObject) {
        foreach ($wnode->children() as $nodeName => $node) {
            if (in_array($nodeName, array("wPane","wCheckBox", "wInput", "wForm", "wPassword", "wLabel", "wButton", "wInputDate", "wListBox", "wTextArea", "wHidden", "wGrid", "wInlineImage","wInputSearchable","wListBoxSearch"))) {
                $atts = $node->attributes();

                //debug("Registering Class: $nodeName Name: {$atts["name"]} Parent: {$lastParent->id}", "green");
                //if (strlen("{$atts["id"]}") == 0) {
					if (strlen("{$atts["name"]}") > 0)
						$obj = new $nodeName("{$atts["name"]}", $lastParent);
					else
						$obj = new $nodeName(null, $lastParent);
				//} else {
					//$obj = new $nodeName("{$atts["id"]}", $lastParent,false);

				//}

                /* Custom object optimizations */
                if ($nodeName == "wForm")
                    $obj->LineByLine = true;


                foreach ($node->attributes() as $attName => $attValue) {
                    if ($attName !== "name") {

                        $methHack = "set" . ucFirst($attName);

                        if (method_exists($obj, $methHack))
                            $obj->$methHack("$attValue");
                        else
                            $obj->$attName = "$attValue";
                    }
                }

                if (strlen("{$atts["name"]}") > 0) {
                    $parentObject->formComponents["{$atts["name"]}"] = $obj;
                    if (!isset($parentObject->_fc))
                        $parentObject->_fc = new stdClass();
                    $_np = "{$atts["name"]}";
                    $parentObject->_fc->$_np = $obj;
                }
				if (method_exists($obj,"__postInit"))
					$obj->__postInit();
				

                if (sizeof($node->children()) > 0) {
                    $this->wObjectWalk($node, $obj, $parentObject);
                }
            } else {
                $atts = $node->attributes();
                if (strlen("{$atts["id"]}") > 0)
                    $obj = new wHTMLComponent("{$atts["id"]}", $lastParent);
                else
                    $obj = new wHTMLComponent(null, $lastParent);
                $obj->setHTML($nodeName);
				if ($nodeName!="br")
					$obj->htmlContent = (string) $node;

                foreach ($node->attributes() as $attName => $attValue)
                    if (!is_object($attValue))
                        $obj->setAtt($attName, $attValue);
                    else
                        $obj->setAtt($attName, "$attValue");

				if (method_exists($obj,"__postInit")) 
					$obj->__postInit();
				
                if (sizeof($node->children()) > 0) {
                    $this->wObjectWalk($node, $obj, $parentObject);
                }
            }
        }
        return $code;
    }

    function parseFromTemplate($file, &$targetwObject) {

        if (!($fp = c_fopen($file, "r"))) {
            die(debug("could not open XML input $file", "red"));
        }
        while ($tdata = fread($fp, 4096))
            $data.=$tdata;

        $parsedData=preg_replace("/###([a-zA-Z_]+)###/e",'$GLOBALS["SYS"][$1]',$data);
        try {
            $xml = new SimpleXMLElement($parsedData);
        } catch (Exception $e) {
            die("XML ERROR<pre>$e</pre>");
        }
		debug("before parseFromTemplate Timestamp: ". (getmicrotime()-$GLOBALS["CODEINITTIME"])." ".__FILE__." ".__LINE__,"green");
        $this->wObjectWalk($xml, $this, $targetwObject);
		debug("after parseFromTemplate Timestamp: ". (getmicrotime()-$GLOBALS["CODEINITTIME"])." ".__FILE__." ".__LINE__,"green");
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
