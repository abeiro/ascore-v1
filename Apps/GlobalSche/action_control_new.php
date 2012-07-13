<?php

/* Enabling AJAX */

require("GlobalSche.php");

set_include_dir(dirname(__FILE__) . "/../../Framework/Extensions/xajax/-");
if ((empty($_POST)) && (!$_GET["oDataRequest"]))
    require('Extensions/wGui/wGui.includes.php');
include 'xajax_core/xajax.inc.php';
require_once("Extensions/wGui/wUI.php");
require_once("gspanel_class.php");

function ManageCheckActivadas($source, $event, $on) {
    debug("ENTRANDO EN MANAGE", "green");
    global $ControlWindow;
    $objResponse = new xajaxResponse();

    if ($on == "on") {
        $_SESSION[$ControlWindow->GSPAnel->id]["showfinished"] = true;
    } else
        $_SESSION[$ControlWindow->GSPAnel->id]["showfinished"] = false;
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

class Desktop {

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
        if (is_array($obj->Listener)) {
            foreach ($obj->Listener as $n => $singleListener) {
                debug("Register Listener $n : " . print_r($singleListener->sourceData, true), "green");
                debug("Confirmation {$singleListener->sourceData["objid"]}: " . $GLOBALS["ObjectHashMap"][$singleListener->sourceData["objid"]]->__internalid, "red");
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
        $this->__createObjectHashMap($this->FormWindow);
        $this->__restoreListeners($this->FormWindow);

        //die();
    }

    function __construct() {


        $this->FormWindow = new wWindow("FormWindow");
        $this->FormWindow->type = WINDOW . NORMAL;
        $this->FormWindow->title = "Tareas";
        $this->FormWindow->setCSS("width", "1000px");

        $this->label = new wLabel("Mostrar terminadas", $this->FormWindow, "Mostrar terminadas");
        $this->ShowTerminadas = new wCheckBox("Terminadas", $this->FormWindow);
        $this->ShowTerminadas->setChecked(true);


        $this->ShowTerminadas->addListener("onclick", "ManageCheckActivadas");
        $this->ShowTerminadas->Listener["onclick"]->addParameter(XAJAX_JS_VALUE, '$("' . $this->ShowTerminadas->id . '").checked');




        $this->GSPAnel = new GSControlPanel($this->FormWindow, "Registros", "gtasklog");
        $_SESSION[$this->GSPAnel->id]["showfinished"] = true;
        if ($_SESSION[$this->GSPAnel->id]["showfinished"]) {
            $this->GSPAnel->SQL_CONDS["gtasklog"] = "((estado<>'Terminada') OR (fin>" . (time() - 60 * 60 * 24 * 7) . " AND estado='Terminada'))";
            $this->ShowTerminadas->setChecked(true);
        } else {
            $this->GSPAnel->SQL_CONDS["gtasklog"] = "((estado<>'Terminada'))";
            $this->ShowTerminadas->setChecked(false);
        }
        $this->GSPAnel->SQL_SORT["gtasklog"] = "inicio DESC,estado ASC";

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
        $this->initiateButton2= new wButton("closebutton2", $this->GSPAnel->aForms[1]->buttonPane);
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

}

$xajax = new xajax();
$xajax->register(XAJAX_FUNCTION, "afunctionwhosnevercalled");
$stub = new StubClass();
$xajax->register(XAJAX_CALLABLE_OBJECT, $stub);

/*
 * Experimental object caching
 */

$DISABLE_CACHING = false;
if ((empty($_POST)) || (!$_SESSION["desktopaxot"])) {

    $ControlWindow = new Desktop();
    unset($_SESSION["desktopaxot"]);
    $_SESSION["desktopaxot"]["id"] = md5(time());
    $_SESSION["desktopaxot"]["panel"] = serialize($ControlWindow);

    debug("Building real version", "red");
} else {
    debug("Using serialized version", "red");
    $f=fopen("/tmp/log.txt","wt");
    $log=print_r(unserialize($_SESSION["desktopaxot"]["panel"]),true);
    fwrite($f,$log,strlen($log));
    fclose(f);
    $a = new wInputDate();
    $a = new wButton();
    if ($DISABLE_CACHING) {
        $ControlWindow = new Desktop();
        unset($_SESSION["desktopaxot"]);
        $_SESSION["desktopaxot"]["id"] = md5(time());
        $_SESSION["desktopaxot"]["panel"] = serialize($ControlWindow);
    }
    else
        $ControlWindow = unserialize($_SESSION["desktopaxot"]["panel"]);
}




if ((empty($_POST)) && (!$_GET["oDataRequest"])) {

    $ControlWindow->FormWindow->render();
//$window->render();
    $xajax->printJavascript($SYS["ROOT"] . "/Framework/Extensions/xajax");
} else if ($_GET["oDataRequest"]) {
    /* Data Request */
    /* $classname=$_GET["oDataRequest"];
      $o=new $classname(null,$_GET["instance"]); */

    $ControlWindow->GSPAnel->aj_RequestData($_GET["instance"]);
} else {


    $xajax->processRequest();
    debug("End", "red");
}
?>
