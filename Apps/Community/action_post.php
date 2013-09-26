<?php

/*
  We usually require asCore API via module main include
 */
require("Community.php");

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

class PostsWindow extends wDesktop {

    var $GSPAnel;
    var $FormWindow;

    function __construct($name = null, &$parent) {

        parent::__construct($name, $parent);

        $this->FormWindow = new wWindow("Post", $this);
        $this->FormWindow->type = WINDOW . NORMAL;
        $this->FormWindow->title = "Post Window";
        $this->FormWindow->setCSS("width", "1000px");
        $this->FormWindow->setCSS("height", "900px");
        $this->GSPAnel = new wUtGridForm($this->FormWindow, "post", "post");

		
		
    }


	
    

}

/* Main Flow starting here */

wDesktop::prepareForDesktop();
$xajax = new xajax();
$xajax->configure("requestURI", $xajax->aSettings["requestURI"] . "&desktop_id={$GLOBALS["desktop_id"]}");

$ControlWindow = wDesktop::createApp("PostsWindow");


if ((empty($_POST)) && (!$_GET["oDataRequest"])) {
    
    $ControlWindow->FormWindow->render();
    $xajax->printJavascript($SYS["ROOT"] . "/Framework/Extensions/xajax");
    jsAction("$('{$ControlWindow->FormWindow->id}_max').onclick()");

} else if ($_GET["oDataRequest"]) {
        $ControlWindow->GSPAnel->aj_RequestData($_GET["instance"]);
} else {
    $xajax->processRequest();
    
}
?>
