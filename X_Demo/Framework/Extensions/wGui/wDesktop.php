<?php
class wDesktop extends wObject {

    function __createObjectHashMap(&$obj) {


        if (is_object($obj))
            if (is_subclass_of($obj, "wObject")) {
                $GLOBALS["ObjectHashMap"][$obj->__internalid] = $obj;
                if (is_array($obj->wChildren)) {
                    foreach ($obj->wChildren as $name => $objC)
                        $this->__createObjectHashMap($objC);
                }
            }
    }

    function __restoreListeners(&$obj) {
        global $xajax;
        //debug("Checking object {$obj->name}. Childs: ".array_keys(get_object_vars($obj->wChildren)),"yellow");

        if (is_array($obj->Listener)) {
            foreach ($obj->Listener as $n => $singleListener) {
                //debug("Register Listener $n : " . print_r($singleListener->sourceData, true), "green");
                //debug("Confirmation {$singleListener->sourceData["objid"]}: " . $GLOBALS["ObjectHashMap"][$singleListener->sourceData["objid"]]->__internalid, "red");
                $currentObject = &$GLOBALS["ObjectHashMap"][$singleListener->sourceData["objid"]];

                if ($singleListener->sourceData["type"] == XAJAX_CALLABLE_OBJECT)
                    $cList = $obj->addListener($n, $singleListener->sourceData["function"], $currentObject);
                else
                    $cList = $obj->addListener($n, $singleListener->sourceData["function"]);



                foreach ($singleListener->sourceData["parameters"] as $sp)
                    $cList->addParameter($sp[0], $sp[1]);
            }
        } else {
            debug(get_class($objC) . " has no listeners", "yellow");
        }

        if (is_array($obj->wChildren)) {
            foreach ($obj->wChildren as $name => $objC)
                if (is_object($objC))
                    if (is_subclass_of($objC, "wObject"))
                        $this->__restoreListeners($objC);
                    else
                        debug(get_class($objC) . " no subclass of wObject", "red");
                else
                    debug(get_class($objC) . " is not an object", "red");
        }
        else {
            debug("{$obj->wChildren} is no an array ", "red");
        }
    }

    function __wakeup() {
        debug("wake up called", "red");
        //$this->restoreListeners($this->FormWindow);
        $this->__createObjectHashMap($this);
        $this->__restoreListeners($this);

        //die();
    }

           
    
    function updateCache() {
          $_SESSION["desktopaxot"]["panel"][$GLOBALS["desktop_id"]] = serialize($this);
        
    }

    public static function ObjCacheRestore($id) {
        
            debug("Using object caching","blue");
            $obj=unserialize($_SESSION["desktopaxot"]["panel"][$id]);
            if (is_object($obj))
                return $obj;
            else  {
                debug("Cached object is invalid","red");
                return null;
            }
        
        
    }
    public static function ObjCacheSave(&$obj,$id) {
        
        $_SESSION["desktopaxot"]["panel"][$id]=serialize($obj);
            
    }
    
    function __construct() {
        
        
    }
}

?>