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
class UserApp extends wDesktop
{
	var $GSPAnel;
	var $FormWindow;
	function __construct($name = null, &$parent)
	{
		parent::__construct($name, $parent);
		$this->FormWindow = new wWindow("Users", $this);
		$this->FormWindow->type = WINDOW . NORMAL;
		$this->FormWindow->title = "Users";
		$this->FormWindow->setCSS("width", "1000px");
		$this->FormWindow->setCSS("height", "900px");
		$this->GSPAnel = new wUtGridForm($this->FormWindow, "Users", "user");
		

		/*
		* Customizing Controls
		*/
		// Group
		$g = newObject("group");
		$originalControl = $this->GSPAnel->dForm->getChildByName("grupos");
		$groupControl = new wListBox("grupos");
		$groupControl->moreHTMLproperties = "multiple";
		$groupControl->setDataModel($g->listGroupIndex());
		$this->GSPAnel->dForm->replace($groupControl, $originalControl->__internalid); // Replace It in Child array
		$this->GSPAnel->dForm->components["grupos"] = $groupControl; // Replace It in component array

		// Password
		$originalPasswordControl = $this->GSPAnel->dForm->getChildByName("password");
		$passControl = new wPassword("passwordEdit");
		$passControl->editMode=true;
		$passControl->placeholder=_("Type password");
		$passControl->setStyle("display:none");
		$this->GSPAnel->dForm->replace($passControl, $originalPasswordControl->__internalid); // Replace It in Child array

		
		

		/* Add extra buttons */
		$this->changePassword = new wButton("changepassword", $this->GSPAnel->aForms[0]->buttonPane,_("Change Password"));
		$this->changePassword->_setDefaults();
		$this->changePassword->addListener("onclick", "ChangePassword",$this);
		$this->changePassword->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $this->GSPAnel->aForms[0]->id);

		$this->changeto = new wButton("changeto", $this->GSPAnel->aForms[0]->buttonPane,_("Login as"));
		$this->changeto->_setDefaults();
		$this->changeto->addListener("onclick", "ChangeTo",$this);
		$this->changeto->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $this->GSPAnel->aForms[0]->id);

		/*$this->resetPassword = new wButton("resetPassword", $this->GSPAnel->aForms[0]->buttonPane,_("Reset Password"));
		$this->resetPassword->_setDefaults();
		$this->resetPassword->addListener("onclick", "resetPassword",$this);
		$this->resetPassword->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $this->GSPAnel->aForms[0]->id);		
		*/
	}

	public function ChangePassword($source, $event, $formData) {
	
		$objResponse = new xajaxResponse();
		$objResponse->assign(".passwordEdit","style.display","block");
		

		return $objResponse;
	}

	public function ChangeTo($source, $event, $formData) {
	
		$objResponse = new xajaxResponse();
		if ($formData["ID"]<3) {
		  $objResponse->alert(_('Cannot login as admin'));
		  return $objResponse;
		}
		$user=newObject("user",$formData["ID"]);
		$_SESSION["__auth"]["backto"]=BILO_uid();
		$_SESSION["__auth"]["username"]=$user->username;
		$_SESSION["__auth"]["uid"]=$user->ID;
		session_commit();

		$objResponse->script("parent.window.location.href='{$GLOBALS["SYS"]["ROOT"]}'");

		return $objResponse;
	}


      public function ResetPassword($source, $event, $formData) {
	
		$objResponse = new xajaxResponse();
		$objResponse->assign(".passwordEdit","style.display","block");
		$objResponse->assign(".passwordEdit2","style.display","block");

		return $objResponse;
	}
	/*
	function updateList($source, $event, $values) {
	$objResponse = new xajaxResponse();
	if (empty($values["people_id_searchbox"])) {    // Reset.
	$objResponse->script("autoCompleteSelect('{$source}_autosg','{$this->GSPAnel->dForm->components["people_id"]->id}',
	0)");
	return $objResponse;
	}

	$o=newObject("people");
	$term=strtr($values["people_id_searchbox"],array(" "=>"%"));
	$foundData=$o->listAll("nameSurname",false,"CONCAT(name,surname) LIKE '%$term%'") ;
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

	*/
	function afterrequestloadFromId(&$objResponse, $obj, $fjid)
	{
		/* Special handler for group control */
		$activeGroups = $obj->coreObject->listGroupsIndex();
		$objResponse->script("SetMultiSelect('.grupos'," . json_encode(array_keys($activeGroups)) . ")");

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
$ControlWindow = wDesktop::createApp("UserApp");
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