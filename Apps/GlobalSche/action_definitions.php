<?php

/* Enabling AJAX */

require("GlobalSche.php");

set_include_dir(dirname(__FILE__) . "/../../Framework/Extensions/xajax/-");
if ((empty($_POST)) && (!$_GET["oDataRequest"]))
    require('Extensions/wGui/wGui.includes.php');
include 'xajax_core/xajax.inc.php';
require_once("Extensions/wGui/wUI.php");
require_once("gspanel_class.php");


$xajax = new xajax();

$layout = new wLayoutTable("mainLayout");
$layout->setHorizontal();
$layout->fixedSizes = array("75px", "100%");
$buttonPanel = new wPane("buttonPane", $layout);

$buttonTareas = new wImage("Tareas", $buttonPanel);
$buttonTareas->label = "Definición de Tareas";
$buttonTareas->src = $SYS["ROOT"] . "/Apps/GlobalSche/local/Img/tareas.png";
$buttonTareas->addListener("onclick", "activateWindowTasks");
$buttonTareas->setCSS("border", "1px solid gray");

$buttonSche = new wImage("Planificaciones", $buttonPanel);
$buttonSche->label = "Planificaciones disponibles";
$buttonSche->src = $SYS["ROOT"] . "/Apps/GlobalSche/local/Img/planificaciones.png";
$buttonSche->addListener("onclick", "activateScheTasks");

$buttonHost = new wImage("Hosts", $buttonPanel);
$buttonHost->label = "Hosts";
$buttonHost->src = $SYS["ROOT"] . "/Apps/GlobalSche/local/Img/host.png";
$buttonHost->addListener("onclick", "activateHostWindow");

$mainPanel = new wPane("rootFakedWindow", $layout);
$mainPanel->setCSS("background-color", "white");
$mainPanel->setCSS("border", "1px");
$mainPanel->setCSS("width", "100%");
$mainPanel->setCSS("min-height", "660px");
$mainPanel->setCSS("position", "relative");



/* Tareas */
$FormWindow = new wWindow("FormWindow", $mainPanel, false);

$FormWindow->title = "Tareas Definidas";
$FormWindow->setCSS("display", "none");
$FormWindow->setCSS("width", "1100px");


$GSPAnel = new GSControlPanel($FormWindow, "Tareas", "gtask");
$GSPAnel->addTab("Pasos", "gstep", "gtask_id");

$GSPAnel->SQL_SORT["gtask"]="ID DESC";
$GSPAnel->SQL_CONDS["gtask"]=$_SESSION["SQL_CONDS"]["gtask"];



$TestTaskButton = new wButton("runTestTask", $GSPAnel->aForms[1]->buttonPane);
$TestTaskButton->label = "Probar";
$TestTaskButton->addListener("onclick", "runTestTask");
$TestTaskButton->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $GSPAnel->aForms[1]->id);


$copyTaskButton = new wButton("copyTask", $GSPAnel->aForms[0]->buttonPane);
$copyTaskButton->label = "Crear Copia";
$copyTaskButton->addListener("onclick", "copyTask");
$copyTaskButton->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $GSPAnel->aForms[0]->id);

/* Programaciones */

$FormWindowSche = new wWindow("FormWindowSche", $mainPanel, false);
$FormWindowSche->setCSS("display", "none");
$FormWindowSche->title = "Programaciones Definidas";
$FormWindowSche->setCSS("width", "1100px");
$GSPAnelSche = new GSControlPanel($FormWindowSche, "Planificaciones", "schedule");

/* Hosts */

$FormWindowHost = new wWindow("FormHost", $mainPanel, false);
$FormWindowHost->setCSS("display", "none");
$FormWindowHost->title = "Conexiones";
$FormWindowHost->setCSS("width", "1100px");
$GSPAnelScheHost = new GSControlPanel($FormWindowHost, "Conexiones", "ghost");

if ((empty($_POST)) && (!$_GET["oDataRequest"])) {

    $layout->render();
    //$FormWindowSche->render();
    $xajax->printJavascript($SYS["ROOT"] . "/Framework/Extensions/xajax");
} else if ($_GET["oDataRequest"]) {
    /* Data Request */
    if (($_GET["instance"] == "gtask") || ($_GET["instance"] == "gstep"))
        $GSPAnel->aj_RequestData($_GET["instance"]);
    else if ($_GET["instance"] == "schedule")
        $GSPAnelSche->aj_RequestData($_GET["instance"]);
    else if ($_GET["instance"] == "ghost")
        $GSPAnelScheHost->aj_RequestData($_GET["instance"]);
}
else {
    $xajax->processRequest();
    debug("End", "red");
}


function activateWindowTasks($event, $id) {
    $objResponse = new xajaxResponse();
    $objResponse->script('$("FormWindow").style.display=($("FormWindow").style.display=="block")?"none":"block";CallAllHandlers()');
    return $objResponse;
    debug("I'm alive", "green");
}

function activateHostWindow($event, $id) {
    $objResponse = new xajaxResponse();
    $objResponse->script('$("FormHost").style.display=($("FormHost").style.display=="block")?"none":"block";CallAllHandlers()');
    return $objResponse;
    debug("I'm alive", "green");
}

function activateScheTasks($event, $id) {
    $objResponse = new xajaxResponse();
    $objResponse->script('$("FormWindowSche").style.display=($("FormWindowSche").style.display=="block")?"none":"block";CallAllHandlers()');
    return $objResponse;
    debug("I'm alive", "green");
}

function copyTask($event, $id,$formData) {
    $oldID=$formData["ID"];
    debug(print_r($formData,true),"white");
    $objResponse = new xajaxResponse();
    $task=newObject("gtask",$oldID);
    $task->ID=0;
    $task->titulo="Copia de {$task->titulo}";
    $task->activa="No";
    $newId=$task->Save();
    
    $steps=newObject("gstep");
    
    foreach ($steps->listAll("ID", false, "gtask_id=$oldID") as $sid=>$singleStep) {
        $step=newObject("gstep",$sid);
        $step->ID=0;    
        $step->gtask_id=$newId;
        $step->Save();
    }
        
    
    $objResponse->script('CallAllHandlers()');
    return $objResponse;
    
}

function runTestTask($event, $id, $formData) {
    global $GSPAnel;
    $objResponse = new xajaxResponse();
    $task = newObject("gstep");
    $task->setAll($formData);

    if ($task->ID < 2)
        $objResponse->script("alert('Selecciona un paso primero')");
    else {
        $status = $task->run();
        $MSG = ($status) ? "Exito" : "Error";
        $objResponse->script("alert(base64_decode('" . base64_encode("$MSG {$task->TMPOUTPUT} {$task->ERROR}") . "'))");
    }

    return $objResponse;
}

?>
