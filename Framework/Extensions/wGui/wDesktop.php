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

    function __restoreListeners(&$obj,&$counter=0) {
        global $xajax;
        debug("Checking object {$obj->name}. Childs: ".array_keys(get_object_vars($obj->wChildren)),"yellow");

        if (is_array($obj->Listener)) {
            foreach ($obj->Listener as $n => $singleListener) {
                //debug("Register Listener $n : " . print_r($singleListener->sourceData, true), "green");
                //debug("Confirmation {$singleListener->sourceData["objid"]}: " . $GLOBALS["ObjectHashMap"][$singleListener->sourceData["objid"]]->__internalid, "red");
                $currentObject = &$GLOBALS["ObjectHashMap"][$singleListener->sourceData["objid"]];

                if ($singleListener->sourceData["type"] == XAJAX_CALLABLE_OBJECT)
                    $cList = $obj->addListener($n, $singleListener->sourceData["function"], $currentObject);
                else
                    $cList = $obj->addListener($n, $singleListener->sourceData["function"]);

                $counter++;

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
                        $this->__restoreListeners($objC,$counter);
                    else
                        nodebug(get_class($objC) . " no subclass of wObject", "red");
                else
                    nodebug(get_class($objC) . " is not an object", "red");
        }
        else {
            nodebug("{$obj->wChildren} is no an array ", "red");
        }
    }

    function __wakeup() {
        debug("wake up called", "red");
        $counter=0;
        //$this->restoreListeners($this->FormWindow);
        $this->__createObjectHashMap($this);
        $this->__restoreListeners($this,$counter);
        debug("Total listeners $counter", "magenta");

        //die();
    }

    function updateCache() {
        $_SESSION["desktopaxot"]["panel"][$GLOBALS["desktop_id"]] = serialize($this);
    }

    public static function ObjCacheRestore($id) {

        debug("OBJCACHE: Using object caching", "magenta");
        $obj = unserialize($_SESSION["desktopaxot"]["panel"][$id]);
        if (is_object($obj))
            return $obj;
        else {
            debug("OBJCACHE: ERR: Cached object is invalid", "red");
            debug(print_r($_SESSION["desktopaxot"]["panel"][$id],true), "red");
            return null;
        }
    }

    public static function ObjCacheSave(&$obj, $id) {

        $_SESSION["desktopaxot"]["panel"][$id] = serialize($obj);
    }

    function __construct() {
        
    }

    public static function prepareForDesktop() {
        if ($_GET["desktop_id"])
            $GLOBALS["desktop_id"] = $_GET["desktop_id"];
        else
            $GLOBALS["desktop_id"] = md5(time() . rand(1, 1000));
    }

    public static function createApp($classname) {
        if ((empty($_POST)) && (!$_GET["oDataRequest"])) {
            debug("OBJCACHE: Building real version: {$GLOBALS["desktop_id"]}", "magenta");
            $obj = new $classname(false);
            wDesktop::ObjCacheSave($obj, $GLOBALS["desktop_id"]);
        } else {
            debug("OBJCACHE: Using serialized version of {$GLOBALS["desktop_id"]}", "magenta");
            $obj = wDesktop::ObjCacheRestore($GLOBALS["desktop_id"]);
			if ($obj==null) {
				 debug("OBJCACHE: Rebuilding {$GLOBALS["desktop_id"]}", "magenta");
				$obj = new $classname(false);
				wDesktop::ObjCacheSave($obj, $GLOBALS["desktop_id"]);
			}
				
        }
        return $obj;
    }

}

?>