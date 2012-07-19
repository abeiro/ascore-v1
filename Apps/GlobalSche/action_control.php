<?php

/* Enabling AJAX */

require("GlobalSche.php");

set_include_dir(dirname(__FILE__) . "/../../Framework/Extensions/xajax/-");
if ((empty($_POST)) && (!$_GET["oDataRequest"]))
    require('Extensions/wGui/wGui.includes.php');
include 'xajax_core/xajax.inc.php';
require_once("Extensions/wGui/wUI.php");
require_once("gspanel_class.php");

/* Desktop */

if ($_GET["desktop_id"])
    $GLOBALS["desktop_id"] = $_GET["desktop_id"];
else
    $GLOBALS["desktop_id"] = md5(time() . rand(1, 1000));

$xajax = new xajax();
$xajax->configure("requestURI", $xajax->aSettings["requestURI"] . "&desktop_id={$GLOBALS["desktop_id"]}");

function ManageEtiqueta($source, $event, $on) {
    debug("ENTRANDO EN MANAGE  ManageEtiqueta $on", "green");
    global $ControlWindow;
    $objResponse = new xajaxResponse();

    if (strlen(trim($on)) > 0) {
        $_SESSION[$GLOBALS["desktop_id"]][$ControlWindow->GSPAnel->id]["etiqueta"] = $on;
    } else
        $_SESSION[$GLOBALS["desktop_id"]][$ControlWindow->GSPAnel->id]["etiqueta"] = "";
    unset($_SESSION["desktopaxot"]);
    $ControlWindow->setQuery();
    $ControlWindow->updateCache();

    session_write_close();
    $objResponse->script("tableGrid_{$ControlWindow->GSPAnel->dGrid->id}.refresh()");
    $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$ControlWindow->GSPAnel->aForms[0]->id}','gtasklog')");



    return $objResponse;
}

function ManageResponsable($source, $event, $on) {
    debug("ENTRANDO EN MANAGE $on", "green");
    global $ControlWindow;
    $objResponse = new xajaxResponse();

    if ($on > 1) {
        $_SESSION[$GLOBALS["desktop_id"]][$ControlWindow->GSPAnel->id]["responsable"] = $on;
    } else
        $_SESSION[$GLOBALS["desktop_id"]][$ControlWindow->GSPAnel->id]["responsable"] = "";
    $ControlWindow->setQuery();
    $ControlWindow->updateCache();
    session_write_close();

    $objResponse->script("tableGrid_{$ControlWindow->GSPAnel->dGrid->id}.refresh()");

    $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$ControlWindow->GSPAnel->aForms[0]->id}','gtasklog')");

    return $objResponse;
}

function ManageCheckActivadas($source, $event, $on) {
    debug("ENTRANDO EN MANAGE ManageCheckActivadas", "green");
    global $ControlWindow;
    $objResponse = new xajaxResponse();

    if ($on == "on") {
        $_SESSION[$GLOBALS["desktop_id"]][$ControlWindow->GSPAnel->id]["showfinished"] = true;
    } else
        $_SESSION[$GLOBALS["desktop_id"]][$ControlWindow->GSPAnel->id]["showfinished"] = false;
    $ControlWindow->setQuery();
    $ControlWindow->updateCache();
    session_write_close();

    $objResponse->script("tableGrid_{$ControlWindow->GSPAnel->dGrid->id}.refresh()");

    $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$ControlWindow->GSPAnel->aForms[0]->id}','gtasklog')");



    return $objResponse;
}

function ManageCheckManual($source, $event, $on) {
    debug("ENTRANDO EN MANAGE MANUAL", "green");
    global $ControlWindow;
    $objResponse = new xajaxResponse();

    if ($on == "on") {
        $_SESSION[$GLOBALS["desktop_id"]][$ControlWindow->GSPAnel->id]["showOnlyManual"] = true;
    } else
        $_SESSION[$GLOBALS["desktop_id"]][$ControlWindow->GSPAnel->id]["showOnlyManual"] = false;
    $ControlWindow->setQuery();
    $ControlWindow->updateCache();
    session_write_close();

    $objResponse->script("tableGrid_{$ControlWindow->GSPAnel->dGrid->id}.refresh()");

    $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$ControlWindow->GSPAnel->aForms[0]->id}','gtasklog')");



    return $objResponse;
}

function runTarea($source, $event, $formData) {
    global $ControlWindow;
    $objResponse = new xajaxResponse();
    $task = newObject("gtasklog", $formData["ID"]);

    if ($task->ID < 2)
        $objResponse->script("alert('Selecciona una tarea primero')");
    else {
        $task->setStatus("ini");
        debug("php5 " . dirname(__FILE__) . "/cmd_spawn_task.php {$formData["ID"]}  &>/tmp/task_{$formData["ID"]}.log&", "red");
        system("(php5 " . dirname(__FILE__) . "/cmd_spawn_task.php {$formData["ID"]} >/tmp/task_{$formData["ID"]}.log) >/dev/null &");

        $objResponse->script("tableGrid_{$ControlWindow->GSPAnel->dGrid->id}.refresh()");
        $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$ControlWindow->GSPAnel->aForms[0]->id}','gtasklog')");
    }

    return $objResponse;
}

function iniciarTarea($source, $event, $formData) {
    global $ControlWindow, $SYS;
    $objResponse = new xajaxResponse();
    $task = newObject("gtasklog", $formData["ID"]);

    if ($task->ID < 2)
        $objResponse->script("alert('Selecciona una tarea primero')");
    else {
        $task->setStatus("ini");
        $objResponse->assign("statusBox", "value", "Tarea iniciada");
        $objResponse->script("tableGrid_{$ControlWindow->GSPAnel->dGrid->id}.refresh()");
        $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$ControlWindow->GSPAnel->aForms[0]->id}','gtasklog')");
    }


    return $objResponse;
}

function terminarTarea($source, $event, $formData) {
    global $ControlWindow;
    $objResponse = new xajaxResponse();
    $task = newObject("gtasklog", $formData["ID"]);

    if ($task->ID < 2)
        $objResponse->script("alert('Selecciona una tarea primero')");
    else {
        if (!$task->setStatus("ter")) {
            $objResponse->script("alert('{$task->ERROR}')");
        } else {
            $objResponse->script("tableGrid_{$ControlWindow->GSPAnel->dGrid->id}.refresh()");
            $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$ControlWindow->GSPAnel->aForms[0]->id}','gtasklog')");
        }
    }

    return $objResponse;
}

function myFormatGridData(&$res) {
    debug("ENTRANDO EN formatGridData", "green");
    foreach ($res as $k => $obj) {
        if (($obj->inicio < time()) && ($obj->estado != 'Terminada')) {
            $res[$k]->etiqueta = "<b style='color:red'>{$obj->etiqueta}</b>";
        }
    }
}

;

/* Ejecutar Paso */

function ejecutarPaso($source, $event, $formData) {
    global $ControlWindow;
    $objResponse = new xajaxResponse();
    $task = newObject("gsteplog", $formData["ID"]);

    if ($task->ID < 2)
        $objResponse->script("alert('Selecciona una tarea primero')");
    else {
        $MSG = $task->run();
        $objResponse->assign("statusBox", "value", "Paso llamado a ejecucion ( {$task->ERROR} )");
        $objResponse->script("CallAllHandlers()");
        $objResponse->script("$('{$ControlWindow->GSPAnel->aForms[1]->id}').reset()");
        $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$ControlWindow->GSPAnel->aForms[1]->id}','gsteplog')");
    }

    return $objResponse;
}

function iniciarPaso($source, $event, $formData) {
    global $ControlWindow;
    $objResponse = new xajaxResponse();
    $task = newObject("gsteplog", $formData["ID"]);

    if ($task->ID < 2)
        $objResponse->script("alert('Selecciona una tarea primero')");
    else {
        $task->setStatus("ini");
        $objResponse->assign("statusBox", "value", "Paso iniciado");
        $objResponse->script("tableGrid_{$ControlWindow->GSPAnel->grids["gsteplog"]->id}.refresh()");
        $objResponse->script("$('{$ControlWindow->GSPAnel->aForms[1]->id}').reset()");
    }



    return $objResponse;
}

function cerrarPaso($source, $event, $formData) {
    global $ControlWindow;
    $objResponse = new xajaxResponse();
    $task = newObject("gsteplog", $formData["ID"]);

    if ($task->ID < 2)
        $objResponse->script("alert('Selecciona una tarea primero')");
    else {
        $task->setStatus("ter");
        $objResponse->assign("statusBox", "value", "Paso iniciado");
        $objResponse->script("tableGrid_{$ControlWindow->GSPAnel->grids["gsteplog"]->id}.refresh()");
        $objResponse->script("$('{$ControlWindow->GSPAnel->aForms[1]->id}').reset()");
    }

    return $objResponse;
}

function checkStatus($source, $event, $formData) {
    global $ControlWindow;
    $objResponse = new xajaxResponse();
    if ($formData["estado"] == "No Iniciada")
        return $objResponse;
    $logData = "<pre>" . file_get_contents("/tmp/task_{$formData["ID"]}.log") . "</pre>";
    $objResponse->script('
      logWin=window.open();
      logWin.document.writeln(base64_decode("' . base64_encode($logData) . '"));
      ');

    return $objResponse;
}

class StubClass {

    function __construct() {
        
    }

}

class MyApp extends wDesktop {

    function __construct() {

        parent::__construct();

        $this->FormWindow = new wWindow("FormWindow", $this);
        $this->FormWindow->type = WINDOW . NORMAL;
        $this->FormWindow->title = "Tareas";
        $this->FormWindow->setCSS("width", "1000px");

        $this->label = new wLabel("Mostrar terminadas", $this->FormWindow, "Mostrar terminadas");
        $this->ShowTerminadas = new wCheckBox("Terminadas", $this->FormWindow);
        $this->ShowTerminadas->setChecked(true);
        $this->ShowTerminadas->addListener("onclick", "ManageCheckActivadas");
        $this->ShowTerminadas->Listener["onclick"]->addParameter(XAJAX_JS_VALUE, '$("' . $this->ShowTerminadas->id . '").checked');

        $this->label = new wLabel("Mostrar sólo manuales", $this->FormWindow, "Mostrar sólo manuales");
        $this->ShowManuales = new wCheckBox("Solo manuales", $this->FormWindow);
        $this->ShowManuales->addListener("onclick", "ManageCheckManual");
        $this->ShowManuales->Listener["onclick"]->addParameter(XAJAX_JS_VALUE, '$("' . $this->ShowManuales->id . '").checked');


        $this->label = new wLabel("Responsable ppal.", $this->FormWindow, "Responsable ppal.");
        $this->Responsable = new wListBox("Responsable", $this->FormWindow, true);
        $userList = newObject("user");

        $this->Responsable->setDataModel($userList->listAll("username", true));
        $this->Responsable->addListener("onchange", "ManageResponsable");
        $this->Responsable->Listener["onchange"]->addParameter(XAJAX_JS_VALUE, '$("' . $this->Responsable->id . '").value');


        $this->label = new wLabel("Etiqueta", $this->FormWindow, "Etiqueta");
        $this->Filtroetiqueta = new wInput("FiltroEtiqueta", $this->FormWindow, true);
        $this->Filtroetiqueta->addListener("onblur", "ManageEtiqueta");
        $this->Filtroetiqueta->Listener["onblur"]->addParameter(XAJAX_JS_VALUE, '$("' . $this->Filtroetiqueta->id . '").value');



        $this->GSPAnel = new GSControlPanel($this->FormWindow, "Registros", "gtasklog");

        /*
         * 
         * Condiciones del QUERY
         */


        $this->setQuery();



        $this->GSPAnel->formatGridData = "myFormatGridData";

        $this->initiateButton = new wButton("initbutton", $this->GSPAnel->dForm->buttonPane);
        $this->initiateButton->label = "Iniciar";
        $this->initiateButton->addListener("onclick", "iniciarTarea");
        $this->initiateButton->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $this->GSPAnel->dForm->id);

        $this->terminateButton = new wButton("terbutton", $this->GSPAnel->dForm->buttonPane);
        $this->terminateButton->label = "Terminar";
        $this->terminateButton->addListener("onclick", "terminarTarea");
        $this->terminateButton->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $this->GSPAnel->dForm->id);

        $this->runButton = new wButton("runbutton", $this->GSPAnel->dForm->buttonPane);
        $this->runButton->label = "Ejecutar";
        $this->runButton->addListener("onclick", "runTarea");
        $this->runButton->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $this->GSPAnel->dForm->id);

        $this->GSPAnel->addTab("Pasos", "gsteplog", "gtasklog_id");
        $this->initiateButton1 = new wButton("initbutton2", $this->GSPAnel->aForms[1]->buttonPane);
        $this->initiateButton1->label = "Iniciar";
        $this->initiateButton1->addListener("onclick", "iniciarPaso");
        $this->initiateButton1->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $this->GSPAnel->aForms[1]->id);
        $this->initiateButton2 = new wButton("closebutton2", $this->GSPAnel->aForms[1]->buttonPane);
        $this->initiateButton2->label = "Finalizar";
        $this->initiateButton2->addListener("onclick", "cerrarPaso");
        $this->initiateButton2->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $this->GSPAnel->aForms[1]->id);

        $this->initiateButton3 = new wButton("runButton", $this->GSPAnel->aForms[1]->buttonPane);
        $this->initiateButton3->label = "Ejecutar";
        $this->initiateButton3->addListener("onclick", "ejecutarPaso");
        $this->initiateButton3->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $this->GSPAnel->aForms[1]->id);

        $this->checkStatus = new wButton("checkstatus", $this->GSPAnel->dForm->buttonPane);
        $this->checkStatus->label = "Estado";
        $this->checkStatus->addListener("onclick", "checkStatus");
        $this->checkStatus->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $this->GSPAnel->dForm->id);
    }

    function setQuery() {
        $additionalCond = " (estado<>'Terminada') ";

        if ($_SESSION[$GLOBALS["desktop_id"]][$this->GSPAnel->id]["showfinished"]) {
            $additionalCond = "(((estado<>'Terminada')) OR (estado='Terminada'))";
            $this->ShowTerminadas->setChecked(true);
        } else
            $this->ShowTerminadas->setChecked(false);

        if ($_SESSION[$GLOBALS["desktop_id"]][$this->GSPAnel->id]["showOnlyManual"]) {
            $additionalCond.=" AND automatica='No'";
            $this->ShowManuales->setChecked(true);
        } else
            $this->ShowManuales->setChecked(false);


        if ($_SESSION[$GLOBALS["desktop_id"]][$this->GSPAnel->id]["responsable"]) {
            $additionalCond.=" AND responsable={$_SESSION[$GLOBALS["desktop_id"]][$this->GSPAnel->id]["responsable"]}";
            $this->Responsable->setSelectedIndex($_SESSION[$GLOBALS["desktop_id"]][$this->GSPAnel->id]["responsable"]);
        }

        if ($_SESSION[$GLOBALS["desktop_id"]][$this->GSPAnel->id]["etiqueta"]) {
            $additionalCond.=" AND etiqueta LIKE '%{$_SESSION[$GLOBALS["desktop_id"]][$this->GSPAnel->id]["etiqueta"]}%'";
            $this->Filtroetiqueta->setSelectedValue($_SESSION[$GLOBALS["desktop_id"]][$this->GSPAnel->id]["etiqueta"]);
        }

        debug("$additionalCond", "yellow");

        $this->GSPAnel->SQL_CONDS["gtasklog"] = "$additionalCond";
        $this->GSPAnel->SQL_SORT["gtasklog"] = "inicio DESC,estado ASC";
    }

}

/*
 * Experimental object caching
 */


if ((empty($_POST)) && (!$_GET["oDataRequest"])) {
    debug("Building real version", "red");
    $ControlWindow = new MyApp(false);
    wDesktop::ObjCacheSave($ControlWindow, $GLOBALS["desktop_id"]);
} else {
    debug("Using serialized version", "red");
    $ControlWindow = wDesktop::ObjCacheRestore($GLOBALS["desktop_id"]);
}




if ((empty($_POST)) && (!$_GET["oDataRequest"])) {

    /* Build and render initial Content */
    $ControlWindow->FormWindow->render();
    $xajax->printJavascript($SYS["ROOT"] . "/Framework/Extensions/xajax");
    jsAction("$('{$ControlWindow->FormWindow->id}_max').onclick()");
} else if ($_GET["oDataRequest"]) {
    /* Data Request */

    $ControlWindow->GSPAnel->aj_RequestData($_GET["instance"]);
} else {
    /* Action Request */
    $xajax->processRequest();
    debug("End", "red");
}
?>
