<?php
/*
We usually require asCore API via module main include
*/
require ("Bilo.php");

/*
Common includes for wGui API
*/
set_include_dir(dirname(__FILE__) . "/../../Framework/Extensions/xajax/-");
require_once 'xajax_core/xajax.inc.php';
require_once ("Extensions/wGui/wUI.php");
require_once ("Extensions/wGui/wUtilities.php");

if ((empty($_POST)) && (!$_GET["oDataRequest"])) 
  require ('Extensions/wGui/wGui.includes.php');

/*
Class driving this desktop
*/
class GroupApp extends wDesktop
{
	var $GSPAnel;
	var $FormWindow;
	function __construct($name = null, &$parent)
	{
		parent::__construct($name, $parent);
		$this->FormWindow = new wWindow("Groups", $this);
		$this->FormWindow->type = WINDOW . NORMAL;
		$this->FormWindow->title = "Groups";
		$this->FormWindow->setCSS("width", "1000px");
		$this->FormWindow->setCSS("height", "900px");
		$this->GSPAnel = new wUtGridForm($this->FormWindow, "Groups", "group");
		

		$deleteButton=$this->GSPAnel->aForms[0]->buttonPane->getChildElementByName("Eliminar");
		$deleteButton->setStyle("display:none");
		$newButton=$this->GSPAnel->aForms[0]->buttonPane->getChildElementByName("Nuevo");
		$newButton->setStyle("display:none");
	}

	

	public function ResetPassword($source, $event, $formData) {
	
		$objResponse = new xajaxResponse();
		$objResponse->assign(".passwordEdit","style.display","block");
		$objResponse->assign(".passwordEdit2","style.display","block");

		return $objResponse;
	}
	
	function afterrequestloadFromId(&$objResponse, $obj, $fjid)
	{
	
		

	}

	function render()
	{
		parent::render();
		$this->FormWindow->render();
	}
}

/* Main Flow starting here */
wDesktop::prepareForDesktop();
$xajax = new xajax();
$xajax->configure("requestURI", $xajax->aSettings["requestURI"] . "&desktop_id={$GLOBALS["desktop_id"]}");
$ControlWindow = wDesktop::createApp("GroupApp");
debug("Filtering conditions. " . print_r($ControlWindow->GSPAnel->SQL_CONDS["terminal"], true) , "yellow");

if ((empty($_POST)) && (!$_GET["oDataRequest"])) {
	$ControlWindow->updateCache();
	$ControlWindow->render();
	$xajax->printJavascript($SYS["ROOT"] . "/Framework/Extensions/xajax");
	jsAction("$('{$ControlWindow->FormWindow->id}_max').onclick()");
}
else
if ($_GET["oDataRequest"]) {
	/* Data Request */
	$ControlWindow->GSPAnel->aj_RequestData($_GET["instance"]);
}
else {
	debug("Aqui", true, "yellow");
	$a = new wListBoxSearch;
	$a->renderS();
	$xajax->processRequest();
	debug("End", "red");
}

?>