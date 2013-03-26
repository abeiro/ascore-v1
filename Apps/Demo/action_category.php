<?php

/*
  We usually require asCore API via module main include
 */
require("Demo.php");

/*
  Common includes for wGui API
 */

set_include_dir(dirname(__FILE__) . "/../../Framework/Extensions/xajax/-");

if ((empty($_POST)) && (!$_GET["oDataRequest"]))
    require('Extensions/wGui/wGui.includes.php');
require_once 'xajax_core/xajax.inc.php';
require_once("Extensions/wGui/wUI.php");
require_once("Extensions/wGui/wUtilities.php");



/*
  Class driving this desktop
 */

class TerminalApp extends wDesktop {

    var $GSPAnel;
    var $FormWindow;

    function __construct($name = null, &$parent) {

        parent::__construct($name, $parent);

        $this->FormWindow = new wWindow("Demo", $this);
        $this->FormWindow->type = WINDOW . NORMAL;
        $this->FormWindow->title = "Demo Window";
        $this->FormWindow->setCSS("width", "1000px");
        $this->FormWindow->setCSS("height", "900px");
        $this->GSPAnel = new wUtGridForm($this->FormWindow, "Categories", "category");
    }

    function updateList($source, $event, $values) {
        $objResponse = new xajaxResponse();
        if (empty($values["callerid"])) {
            $objResponse->script("autoCompleteSelect('{$source}_autosg',{$this->GSPAnel->dForm->components["userref"]->id},
            '{$this->GSPAnel->dForm->components["callerid"]->id}',0,'')");
            return $objResponse;
        }

        $cmdb = cmdb_ds::getInstance();
        $o = $cmdb->_query("SELECT codempleado as eid,nombre||' '||apellido1||' '||apellido2 as nombre FROM nav_usuarios_jerarquia WHERE nombre||' '||apellido1||' '||apellido2 ILIKE '%{$values["callerid"]}%' ORDER BY apellido1,apellido2,nombre ASC LIMIT 0,15");
        while ($data = $cmdb->_fetch_array($o)) {
            $arrayFakeData[$data["eid"]] = $data["nombre"];
        }

        $objResponse->script("autoCompleteShowOptions(" . json_encode($arrayFakeData) . ",'{$source}_autosg','{$this->GSPAnel->dForm->components["userref"]->id}','{$this->GSPAnel->dForm->components["callerid"]->id}')");

        return $objResponse;
    }

    function afterrequestloadFromId($objResponse, $obj, $fjid) {
        debug("fuu:" . print_r($obj->coreObject, true), "yellow");

        
    }

}

/* Main Flow starting here */

wDesktop::prepareForDesktop();
$xajax = new xajax();
$xajax->configure("requestURI", $xajax->aSettings["requestURI"] . "&desktop_id={$GLOBALS["desktop_id"]}");

$ControlWindow = wDesktop::createApp("TerminalApp");
debug("Filtering conditions. " . print_r($ControlWindow->GSPAnel->SQL_CONDS["terminal"], true), "yellow");

if ((empty($_POST)) && (!$_GET["oDataRequest"])) {
    $ControlWindow->updateCache();
    $ControlWindow->FormWindow->render();
    $xajax->printJavascript($SYS["ROOT"] . "/Framework/Extensions/xajax");
    jsAction("$('{$ControlWindow->FormWindow->id}_max').onclick()");
} else if ($_GET["oDataRequest"]) {
    /* Data Request */
    $ControlWindow->GSPAnel->aj_RequestData($_GET["instance"]);
} else {
    $xajax->processRequest();
    debug("End", "red");
}
?>
