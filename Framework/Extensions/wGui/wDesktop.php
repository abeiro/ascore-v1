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
            } else {
					debug("Object not a subclass of wObject!!!!!!!!!!!!","red");
			}
    }

    function __restoreListeners(&$obj,&$counter=0) {
        global $xajax;
        //debug("Checking object {$obj->name}. Childs: ".array_keys(get_object_vars($obj->wChildren)),"yellow");

        if (is_array($obj->Listener)) {
            foreach ($obj->Listener as $n => $singleListener) {
                $currentObject = &$GLOBALS["ObjectHashMap"][$singleListener->sourceData["objid"]];

                if ($singleListener->sourceData["type"] == XAJAX_CALLABLE_OBJECT)
                    $cList = $obj->addListener($n, $singleListener->sourceData["function"], $currentObject);
                else
                    $cList = $obj->addListener($n, $singleListener->sourceData["function"]);

                $counter++;

				//debug("Register Listener $n : ".print_r($singleListener->sourceData,true), "green");

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
		debug("Timestamp: ". (getmicrotime()-$GLOBALS["CODEINITTIME"])." ".__FILE__." ".__LINE__,"green");
        $this->__createObjectHashMap($this);
		debug("Timestamp: ". (getmicrotime()-$GLOBALS["CODEINITTIME"])." ".__FILE__." ".__LINE__,"green");
        $this->__restoreListeners($this,$counter);
        debug("Total listeners $counter", "magenta");
		debug("Timestamp: ". (getmicrotime()-$GLOBALS["CODEINITTIME"])." ".__FILE__." ".__LINE__,"green");

        //die();
    }

    function updateCache() {
		debug("Updating cache {$GLOBALS["desktop_id"]}", "red");
		
        $_SESSION["desktopaxot"]["panel"][$GLOBALS["desktop_id"]] = serialize($this);
		$_SESSION["desktopaxot"]["ttl"][$GLOBALS["desktop_id"]]=time();
		
    }

    public static function ObjCacheRestore($id) {

        debug("OBJCACHE: Using object caching", "magenta");
        $obj = unserialize($_SESSION["desktopaxot"]["panel"][$id]);
		$_SESSION["desktopaxot"]["ttl"][$id]=time();
        if (is_object($obj))
            return $obj;
        else {
            debug("OBJCACHE: ERR: Cached object is invalid", "red");
            debug(print_r($_SESSION["desktopaxot"]["panel"][$id],true), "red");
            return null;
        }
    }

    public static function ObjCacheSave(&$obj, $id) {
		$_SESSION["desktopaxot"]["ttl"][$id]=time();
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
		$_SESSION["desktopaxot"]["ttl"][$GLOBALS["desktop_id"]]=time();
        return $obj;
    }


	public static function set($key,$obj) {
		nodebug("Setting data for $key ".print_r($obj,true),"green");
        $_SESSION["permastore"][$GLOBALS["desktop_id"]][$key]=json_encode($obj);
		$_SESSION["permastorettl"][$GLOBALS["desktop_id"]]=time();
            
    }
	public static function get($key) {
		nodebug("Getting data for $key","green");
		$_SESSION["permastorettl"][$GLOBALS["desktop_id"]]=time();
        return json_decode($_SESSION["permastore"][$GLOBALS["desktop_id"]][$key],true);
    }

	public function render() {
		$time=time();
		echo <<<SCRIPT
<script>
function removeDesktop(arg) {
	console.log('Purging desktop {$GLOBALS["desktop_id"]}');
	xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		console.log(xmlhttp.readyState);
		if (xmlhttp.readyState==4 && xmlhttp.status==200)    {
			
			if (arg!=undefined) {
				console.log("Evaluating: "+arg);
				eval(arg);
			}
		}
	}
	xmlhttp.open("GET","{$GLOBALS["SYS"]["ROOT"]}/Framework/Extensions/wGui/helpers/action_dspurge.php?did={$GLOBALS["desktop_id"]}",false);
	xmlhttp.send();
	
}
function showDesktop() {
	console.log('Showing desktop {$GLOBALS["desktop_id"]}');
	new Ajax.Request('{$GLOBALS["SYS"]["ROOT"]}/Framework/Extensions/wGui/helpers/action_dscheck.php?did={$GLOBALS["desktop_id"]}', { 
		method:'get',
		onSuccess: function(response) {
			alert("Esta ventana ha expirado y la información puede no estar actualizada");
		}
	});
}


</script>
SCRIPT;

	}
	
}

?>