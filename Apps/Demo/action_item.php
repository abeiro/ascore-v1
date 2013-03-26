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
        $this->GSPAnel = new wUtGridForm($this->FormWindow, "Items", "item");
        
        
        /* 
         * Customizing Controls 
         */
                
        $peopleidControl=new wInputSearchable("people_id");                                 // New Control
        $originalControl=$this->GSPAnel->dForm->getChildByName("people_id");                // Get Original Control
        $this->GSPAnel->dForm->replace($peopleidControl,$originalControl->__internalid);    // Replace It in Child array
        $this->GSPAnel->dForm->components["people_id"]=$peopleidControl;                    // Replace It in component array
        $peopleidControl->addListener("ondelayedchange", "updateList", $this);
        $peopleidControl->Listener["ondelayedchange"]->addParameter(XAJAX_FORM_VALUES, $this->GSPAnel->dForm->id);
        $peopleidControl->addListener("onchange", "Updatelabel", $this);
        $peopleidControl->Listener["onchange"]->addParameter(XAJAX_FORM_VALUES, $this->GSPAnel->dForm->id);
                
        
    }

    function updateList($source, $event, $values) {
        $objResponse = new xajaxResponse();
        if (empty($values["people_id_searchbox"])) {    // Reset.
            $objResponse->script("autoCompleteSelect('{$source}_autosg','{$this->GSPAnel->dForm->components["people_id"]->id}',
            0)");
            return $objResponse;
        }

        $o=newObject("people");
        
        $foundData=$o->listAll("nameSurname",false,"CONCAT(name,surname) LIKE '%{$values["people_id_searchbox"]}%'") ;
        asort($foundData);
        
        foreach ($foundData as $id=>$data)
            if ($c++<16)
                $displayedResults[]=array("id"=>$id,"label"=>$data);
        
        $objResponse->script("autoCompleteShowOpts(" . json_encode($displayedResults) . 
                ",'{$source}_autosg','{$this->GSPAnel->dForm->components["people_id"]->id}','{$this->GSPAnel->dForm->components["people_id"]->_searchbox}')");

        return $objResponse;
    }
    
    function updateLabel($source, $event, $values) {
        $objResponse = new xajaxResponse();
        
        $o=newObject("people",$values["people_id"]);
        $objResponse->assign("{$source}_searchbox","value",$o->nameSurname());

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
