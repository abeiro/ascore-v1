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


wDesktop::prepareForDesktop();
$xajax = new xajax();
$xajax->configure("requestURI", $xajax->aSettings["requestURI"] . "&desktop_id={$GLOBALS["desktop_id"]}");

class MyApp extends wDesktop {

    var $tipoMon;
    var $fechaC;
    var $FormWindow;
    var $lay;

    function __construct() {

        parent::__construct();

        $this->FormWindow = new wWindow("FormWindow", $this);
        $this->FormWindow->type = WINDOW . NORMAL;
        $this->FormWindow->title = "Seguimiento";
        $this->FormWindow->setCSS("width", "1000px");
        $this->FormWindow->setCSS("height", "900px");

        $this->lay = new wLayoutTable("controlPane", $this->FormWindow);
        $this->lay->setFree(true);
        $this->lay->setCSS("padding-bottom", "10px");


        $label = new wLabel("", $this->lay, "Seleccione tipo de monitorización");

        $this->tipoMon = new wListBox("tipoMonitorizacion", $this->lay, true);
        $this->tipoMon->setDataModel(array("Mensual" => "Mensual", "Diario" => "Diario"));

        $this->tipoMon->addListener("onchange", "changeView", $this);
        $this->tipoMon->Listener["onchange"]->addParameter(XAJAX_INPUT_VALUE, $this->tipoMon->id);


        $label = new wLabel("", $this->lay, "Seleccione fecha");
        $label->setCSS("padding-left", "20px");
        $this->fechaC = new wInputDate("fechaC", $this->lay, true);
        $this->fechaC->setTime(time());
        $this->fechaC->addListener("onchange", "changeDate", $this);
        $this->fechaC->Listener["onchange"]->addParameter(XAJAX_INPUT_VALUE, $this->fechaC->id);
        $this->fechaC->Listener["onchange"]->addParameter(XAJAX_INPUT_VALUE, $this->tipoMon->id);

        $this->doRefresh = new wButton("fechaC", $this->lay, "Refrescar");

        $this->doRefresh->addListener("onclick", "doRefresh", $this);
        $this->doRefresh->Listener["onclick"]->addParameter(XAJAX_INPUT_VALUE, $this->fechaC->id);
        $this->doRefresh->Listener["onclick"]->addParameter(XAJAX_INPUT_VALUE, $this->tipoMon->id);



        $this->customHtml = new wHTML("htmlVisor", $this->FormWindow);
        $this->customHtml->setCSS("overflow","scroll");
        $this->customHtml->setCSS("max-height","675px");
    }

    public function doRefresh($id, $fjid, $date, $type) {
        $objResponse = new xajaxResponse();

        debug("Timestamp:" . (getmicrotime() - $GLOBALS["CODEINITTIME"]), "green");

        $this->customHtml = new wHTML("htmlVisor", $this->FormWindow);
        $this->show($type, text_to_int($date));
        debug("Timestamp:" . (getmicrotime() - $GLOBALS["CODEINITTIME"]), "green");
        $this->updateCache();
        $objResponse->script("a=$('htmlVisor');a.innerHTML=" . json_encode($this->customHtml->renderS()));
        debug("Timestamp:" . (getmicrotime() - $GLOBALS["CODEINITTIME"]), "green");
        return $objResponse;
    }

    public function changeDate($id, $fjid, $date, $type) {
        $objResponse = new xajaxResponse();


        debug("Visor $data", "white");

        $this->customHtml = new wHTML("htmlVisor", $this->FormWindow);
        $this->show($type, text_to_int($date));
        $this->updateCache();
        $objResponse->script("a=$('htmlVisor');a.innerHTML=" . json_encode($this->customHtml->renderS()));
        return $objResponse;
    }

    public function changeView($id, $fjid, $data) {
        $objResponse = new xajaxResponse();

        debug("Visor $data", "white");

        $this->customHtml = new wHTML("htmlVisor", $this->FormWindow);
        $this->show($data);
        $this->updateCache();
        $objResponse->script("a=$('htmlVisor');a.innerHTML=" . json_encode($this->customHtml->renderS()));
        return $objResponse;
    }

    function show($type, $date = '') {
        if ($date == "")
            $date = time();
        $this->customHtml->setHTML($this->htmlView($type, $date));
        $this->customHtml->setCSS("background-color", "white");
    }

    function htmlView($type, $date) {
        $START=  getmicrotime();
        $evalTimeStamp = $date;
        if ($type == "Mensual") {
            $SQLEXPR = "DATE_FORMAT(FROM_UNIXTIME(inicio),'%Y%m')=DATE_FORMAT(FROM_UNIXTIME($evalTimeStamp),'%Y%m')";
        }
        if ($type == "Diario") {
            $SQLEXPR = "DATE_FORMAT(FROM_UNIXTIME(inicio),'%Y%m%d')=DATE_FORMAT(FROM_UNIXTIME($evalTimeStamp),'%Y%m%d')";
        }
        setLimitRows(1000);
        $buffer = "";
        $o = newObject("gtask");
        $o->searchResults = $o->select("seguimiento='$type' ");
        $idCollection = $o->selectA("seguimiento='$type' ");

        $cachedtasks = newObject("gtasklog");
        if (sizeof($idCollection)==0) {
            return "Sin datos";
        }
        $buffer = $cachedtasks->select("gtask_id IN (" . implode(",", array_keys($idCollection)) . ")  AND $SQLEXPR");

        foreach ($buffer as $k => $v) {
            $cachedResults[$v->gtask_id] = $v;
            $gtasklogCollection[]=$v->ID;
        }

         $steps = newObject("gsteplog");
         if (sizeof($gtasklogCollection)>0) {
            $buffer2=$steps->select("gtasklog_id IN (" . implode(",", $gtasklogCollection) . ") ");
            foreach ($buffer2 as $steplog) {
                $cachedResults[$v->gtask_id] = $v;
                $cachedsStepsLogs[$steplog->gtasklog_id][$steplog->ID]=$steplog;
            }   
         }
        //print_r($buffer);
        //die();
        
        
        foreach ($o->searchResults as $idx => $sobj) {
            $set = array();
            $set["titulo"] = $sobj->titulo;
            $t = $cachedResults[$sobj->ID];
            //$t->searchResults = $t->select("gtask_id={$sobj->ID} and $SQLEXPR");

            if ($t) {
                $todayExec = $t;
                $set["subestado"] = "Encolada";
                $set["estado"] = $todayExec->estado;
                $set["fecha"] = $todayExec->inicio;
            } else {
                $set["subestado"] = "No encolada";
                $set[""] = "";
            }

            //$plan = newObject("schedule", $sobj->schedule_id);
            //$set["plan"] = $plan->referencia;
            $button = new wButton("exec_{$sobj->ID}", $this);
            $button->label = "Ejecutar";
            $button->setDisabled(true);
            $button2 = null;

            if ($set["subestado"] == "No encolada") {
                $button->label = "Encolar";
                $button->addListener("onclick", "enqueueTask", $this);
                $button->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, $sobj->ID);
                $button->setDisabled(false);
                $button->Listener["onclick"]->addParameter(XAJAX_INPUT_VALUE, $this->fechaC->id);
                $button->Listener["onclick"]->addParameter(XAJAX_INPUT_VALUE, $this->tipoMon->id);

                $button2 = new wButton("mark_{$sobj->ID}", $this);
                $button2->label = "Marcar realizada";
                $button2->addListener("onclick", "marksAsDone", $this);
                $button2->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, $sobj->ID);
                $button2->Listener["onclick"]->addParameter(XAJAX_INPUT_VALUE, $this->fechaC->id);
                $button2->Listener["onclick"]->addParameter(XAJAX_INPUT_VALUE, $this->tipoMon->id);
                $button2->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, 'false');
            } else if (($set["subestado"] == "Encolada") && ($set["estado"] == "No Iniciada")) {

                $button->addListener("onclick", "executeTask", $this);
                $button->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, $todayExec->ID);
                $button->setDisabled(false);
                $button->Listener["onclick"]->addParameter(XAJAX_INPUT_VALUE, $this->fechaC->id);
                $button->Listener["onclick"]->addParameter(XAJAX_INPUT_VALUE, $this->tipoMon->id);
                $button->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, 'false');
            }



            $set["boton"] = $button->renderS();
            if ($button2)
                $set["boton2"] = $button2->renderS();

            if ($set["subestado"] == "Encolada") {
                if (($set["estado"] == "No Iniciada") && ( $set["fecha"] < time()))
                    $set["sclass"] = "alertaleve";
                else if (($set["estado"] == "Cancelada"))
                    $set["sclass"] = "alertagrave";
                else if (($set["estado"] == "En Curso"))
                    $set["sclass"] = "atencion";
                else if (($set["estado"] == "Terminada"))
                    $set["sclass"] = "correcto";
            }


            /* Step check */
            $stepstatus = array();
            if (($set["estado"] == "En Curso") || ($set["estado"] == "Terminada") || ($set["estado"] == "Cancelada")) {
                //$steps = newObject("gsteplog");
                //$steps->searchResults = $steps->select("gtasklog_id='{$todayExec->ID}'");
                //foreach ($steps->searchResults as $singleStep) {
                foreach ($cachedsStepsLogs[$todayExec->ID] as $singleStep) {
                    if ($singleStep->estado == 'Terminada')
                        $stepstatus[$singleStep->ID] = "ok";
                    if ($singleStep->estado == 'En Curso')
                        $stepstatus[$singleStep->ID] = "run";
                    if ($singleStep->estado == 'Cancelada')
                        $stepstatus[$singleStep->ID] = "ko";
                }

                foreach ($stepstatus as $sid => $singlestatus) {
                    //$singlesteps = newObject("gsteplog", $sid);
                    $set["stepstatus"].="<img src='{$GLOBALS["SYS"]["ROOT"]}/Apps/GlobalSche/local/Img/$singlestatus.png' title='{$cachedsStepsLogs[$todayExec->ID][$sid]->ultimoerror}'/>&nbsp;";
                }
            }
            $set["comentarios"] = $sobj->comentarios;

            $templateSet[] = $set;
            
        }
        resetLimitRows();



        $tmpl = new asTemplate("monitor");
        $buffer = $tmpl->plParseTemplateHeader(array("date"=>date("d/M/y H:i:s")));
        foreach ($templateSet as $set) {
            $buffer.=$tmpl->plParseTemplateFast($set);
        }

        $buffer.=$tmpl->plParseTemplateFooter();
        debug("Running so far:".(getmicrotime()-$START),"magenta");
        return $buffer;
    }

    public function executeTask($id, $fjid, $sourcetaskid, $fecha, $tipo, $confirm) {
        $objResponse = new xajaxResponse();

        if ($confirm == "false") {
            $objResponse->script("if (confirm('¿Está usted seguro?')) xajax_{$this->getMyClassName()}.executeTask('$id', '$fjid', '$sourcetaskid', '$fecha', '$tipo', 'true')");
        } else {
            $task = newObject("gtasklog", $sourcetaskid);

            if ($task->ID < 2)
                $objResponse->script("alert('Selecciona una tarea primero')");
            else {
                $task->setStatus("ini");
                debug("php5 " . dirname(__FILE__) . "/cmd_spawn_task.php $sourcetaskid  &>/tmp/task_$sourcetaskid.log&", "red");
                system("(php5 " . dirname(__FILE__) . "/cmd_spawn_task.php $sourcetaskid >/tmp/task_$sourcetaskid.log) >/dev/null &");
            }
            $objResponse->script("a=$('htmlVisor');for (i=0;i<a.children.length;i++) {a.removeChild(a.children[i])}");
            $objResponse->script("a=$('htmlVisor');for (i=0;i<a.children.length;i++) {a.removeChild(a.children[i])}");
            $this->customHtml = new wHTML("htmlVisor", $this->FormWindow);
            $this->show($tipo, text_to_int($fecha));
            $this->updateCache();
            $objResponse->script("a=$('htmlVisor');a.innerHTML=" . json_encode($this->customHtml->renderS()));
        }
        return $objResponse;
    }

    public function enqueueTask($id, $fjid, $sourceid, $fecha, $tipo) {
        $objResponse = new xajaxResponse();
        /* */
        $o = newObject("gtask", $sourceid);
        $tl = newObject("gtasklog");
        $TSTAMP = $timeStampOfRun = text_to_int($fecha);

        $tl->etiqueta = $o->titulo . "@" . strftime("%Y%m%d", $TSTAMP);
        $tl->tipo = 'Desde Definición';
        $tl->gtask_id = $sourceid;
        $tl->schedule_id = $o->schedule_id;
        $tl->inicio = $timeStampOfRun;
        $tl->estado = 'No iniciada';
        $tl->automatica = $o->automatica;
        $tl->emailconfirmacion = $o->emailconfirmacion;
        $tl->departamento = $o->departamento;
        $tl->responsable = $o->responsable;
        $tl->diasderetraso = $o->diasderetraso;
        $tl->save();

        $objResponse->script("a=$('htmlVisor');for (i=0;i<a.children.length;i++) {a.removeChild(a.children[i])}");
        $objResponse->script("a=$('htmlVisor');for (i=0;i<a.children.length;i++) {a.removeChild(a.children[i])}");
        $this->customHtml = new wHTML("htmlVisor", $this->FormWindow);
        $this->show($tipo, text_to_int($fecha));
        $this->updateCache();
        $objResponse->script("a=$('htmlVisor');a.innerHTML=" . json_encode($this->customHtml->renderS()));

	$objResponse->script("showPopup()");
        return $objResponse;
    }

    public function marksAsDone($id, $fjid, $sourceid, $fecha, $tipo, $confirm) {
        $objResponse = new xajaxResponse();
        if ($confirm == "false") {
            $objResponse->script("if (confirm('¿Está usted seguro?')) xajax_{$this->getMyClassName()}.marksAsDone('$id', '$fjid', '$sourceid', '$fecha', '$tipo', 'true')");
        } else {
            /* */
            $o = newObject("gtask", $sourceid);
            $tl = newObject("gtasklog");
            $TSTAMP = $timeStampOfRun = time();

            $tl->etiqueta = $o->titulo . "@" . strftime("%Y%m%d", $TSTAMP);
            $tl->tipo = 'Sólo registro';
            $tl->gtask_id = $sourceid;
            $tl->schedule_id = $o->schedule_id;
            $tl->inicio = $timeStampOfRun;
            $tl->estado = 'No iniciada';
            $tl->automatica = $o->automatica;
            $tl->emailconfirmacion = $o->emailconfirmacion;
            $tl->departamento = $o->departamento;
            $tl->responsable = $o->responsable;
            $tl->diasderetraso = $o->diasderetraso;
            $tl->save();
            $tl->SetStatus("ter");

            $this->customHtml = new wHTML("htmlVisor", $this->FormWindow);
            $this->show($tipo, text_to_int($fecha));
            $this->updateCache();
            $objResponse->script("a=$('htmlVisor');a.innerHTML=" . json_encode($this->customHtml->renderS()));
        }
        return $objResponse;
    }

}

debug("Timestamp:" . (getmicrotime() - $GLOBALS["CODEINITTIME"]), "green");
$ControlWindow = wDesktop::createApp("MyApp");
debug("Timestamp after created app:" . (getmicrotime() - $GLOBALS["CODEINITTIME"]), "green");


/* Flow */
if ((empty($_POST)) && (!$_GET["oDataRequest"])) {

    /* Build and render initial Content */
    $ControlWindow->show("Mensual");
    $ControlWindow->updateCache();
    $ControlWindow->FormWindow->render();

    $xajax->printJavascript($SYS["ROOT"] . "/Framework/Extensions/xajax");
    jsAction("$('{$ControlWindow->FormWindow->id}_max').onclick()");
} else if ($_GET["oDataRequest"]) {
    /* Data Request */
    //$ControlWindow->GSPAnel->aj_RequestData($_GET["instance"]);
} else {
    /* Action Request */
    //debug(print_r($ControlWindow, true), "magenta");
    $xajax->processRequest();
    debug("End", "red");
}
?>
