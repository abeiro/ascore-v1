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

    function __construct() {

        parent::__construct();

        $this->FormWindow = new wWindow("FormWindow", $this);
        $this->FormWindow->type = WINDOW . NORMAL;
        $this->FormWindow->title = "Seguimiento";
        $this->FormWindow->setCSS("width", "1000px");
        $this->customHtml = new wHTML("htmlVisor", $this->FormWindow);
        
        
    }
    
    function show() {
        
        $this->customHtml->setHTML($this->htmlView("Mensual"));
        $this->customHtml->setCSS("background-color", "white");
    }

    function htmlView($type) {
        $evalTimeStamp = time();
        if ($type == "Mensual") {
            $SQLEXPR = "DATE_FORMAT(FROM_UNIXTIME(inicio),'%Y%m')=DATE_FORMAT(FROM_UNIXTIME($evalTimeStamp),'%Y%m')";
        }
        if ($type == "Diario") {
            $SQLEXPR = "DATE_FORMAT(FROM_UNIXTIME(inicio),'%Y%m%d')=DATE_FORMAT(FROM_UNIXTIME($evalTimeStamp),'%Y%m%d')";
        }
        setLimitRows(1000);
        $buffer = "";
        $o = newObject("gtask");
        $o->searchResults = $o->select("seguimiento='$type'");
        
       
        foreach ($o->searchResults as $idx=>$sobj) {
            $set=array();
            $set["titulo"]=$sobj->titulo;
            $t = newObject("gtasklog");
            $t->searchResults = $t->select("gtask_id={$sobj->ID} and $SQLEXPR");
            if (sizeof($t->searchResults) > 0) {
                $todayExec = current($t->searchResults);
                
                $set["subestado"]="Encolada";
                $set["estado"]=$todayExec->estado;
                $set["fecha"]=$todayExec->inicio;
            } else {
                $set["subestado"]="No encolada";
                $set[""]="";
            }

            $plan=newObject("schedule",$sobj->schedule_id);
            $set["plan"]=$plan->referencia;
            $button = new wButton("exec_{$sobj->ID}",$this);
            $button->label = "Ejecutar";
            if (sizeof($t->searchResults) > 0)
                $button->setDisabled(true);
            else {
                $button->addListener("onclick", "executeTask",$this);
                $button->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, $sobj->ID);
            }
            $set["boton"]=$button->renderS();
            
            if ($set["subestado"]=="Encolada") {
                if (($set["estado"]=="No Iniciada")&&( $set["fecha"]<time()))
                        $set["sclass"]="alertaleve";
                else if (($set["estado"]=="Cancelada"))
                        $set["sclass"]="alertagrave";
                else if (($set["estado"]=="En Curso"))
                        $set["sclass"]="atencion";
                else if (($set["estado"]=="Terminada"))
                        $set["sclass"]="correcto";
                
            }
            $templateSet[]=$set;
        }
        resetLimitRows();
        
        
        
        $tmpl=new asTemplate("monitor");
        $buffer=$tmpl->plParseTemplateHeader();
        foreach ($templateSet as $set) {
            $buffer.=$tmpl->plParseTemplateFast($set);
        }
        
        $buffer.=$tmpl->plParseTemplateFooter();
        
        return $buffer;
    }
    
    public function  executeTask($id, $fjid, $data) {
        $objResponse = new xajaxResponse();
        $objResponse->script("alert('Casi')");
        return $objResponse;
    }

}

$ControlWindow = wDesktop::createApp("MyApp");


/* Flow */
if ((empty($_POST)) && (!$_GET["oDataRequest"])) {

    /* Build and render initial Content */
    $ControlWindow->show();
    $ControlWindow ->updateCache();
    $ControlWindow->FormWindow->render();
    
    $xajax->printJavascript($SYS["ROOT"] . "/Framework/Extensions/xajax");
    jsAction("$('{$ControlWindow->FormWindow->id}_max').onclick()");
} else if ($_GET["oDataRequest"]) {
    /* Data Request */
    //$ControlWindow->GSPAnel->aj_RequestData($_GET["instance"]);
} else {
    /* Action Request */
    debug(print_r($ControlWindow,true),"magenta");
    $xajax->processRequest();
    debug("End", "red");
}
?>
