<?php
  
  /* Enabling AJAX */
  
  require("GlobalSche.php");
  
  set_include_dir(dirname(__FILE__)."/../../Framework/Extensions/xajax/-");
  if ((empty($_POST))&&(!$_GET["oDataRequest"]))
    require('Extensions/wGui/wGui.includes.php');
  include 'xajax_core/xajax.inc.php';
  require_once("Extensions/wGui/wUI.php");
  require_once("gspanel_class.php");
  
  
  
  $xajax = new xajax();
  
  $FormWindow=new wWindow("FormWindow");
  $FormWindow->type=WINDOW.NORMAL;
  $FormWindow->title="Tareas";
  $FormWindow->setCSS("width","1000px");
  
  $label=new wLabel("Mostrar terminadas",$FormWindow,"Mostrar terminadas");
  $ShowTerminadas=new wCheckBox("Terminadas",$FormWindow);
  $ShowTerminadas->addListener("onclick","ManageCheckActivadas");
  $ShowTerminadas->Listener["onclick"]->addParameter( XAJAX_JS_VALUE,'$("'.$ShowTerminadas->id.'").checked');
  
  
  $label=new wLabel("Mostrar sólo manuales",$FormWindow,"Mostrar sólo manuales");
  $ShowManuales=new wCheckBox("Solo manuales",$FormWindow);
  $ShowManuales->addListener("onclick","ManageCheckManual");
  $ShowManuales->Listener["onclick"]->addParameter( XAJAX_JS_VALUE,'$("'.$ShowManuales->id.'").checked');
  
  
  $label=new wLabel("Responsable ppal.",$FormWindow,"Responsable ppal.");
  $Responsable=new wListBox("Responsable", $FormWindow, true);
  $userList=newObject("user");
  
  $Responsable->setDataModel($userList->listAll("username", true));
  $Responsable->addListener("onchange","ManageResponsable");
  $Responsable->Listener["onchange"]->addParameter( XAJAX_JS_VALUE,'$("'.$Responsable->id.'").value');
  
  
  $label=new wLabel("Etiqueta",$FormWindow,"Etiqueta");
  $Filtroetiqueta=new wInput("FiltroEtiqueta", $FormWindow, true);
  $Filtroetiqueta->addListener("onblur","ManageEtiqueta");
  $Filtroetiqueta->Listener["onblur"]->addParameter( XAJAX_JS_VALUE,'$("'.$Filtroetiqueta->id.'").value');
  
  
  /* 
   * 
   * Action Listenres 
   * 
   */
  
   function ManageEtiqueta($source,$event,$on) {
    debug("ENTRANDO EN MANAGE  ManageEtiqueta $on","green");
    global $GSPAnel;
    $objResponse = new xajaxResponse();
    
    if (strlen(trim($on))>0) {
      $_SESSION[$GSPAnel->id]["etiqueta"]=$on;
    } else
      $_SESSION[$GSPAnel->id]["etiqueta"]="";
    
    session_write_close (  );
    $objResponse->script("tableGrid_{$GSPAnel->dGrid->id}.refresh()");
    $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$GSPAnel->aForms[0]->id}','gtasklog')");
    
    return $objResponse;
  }
  
  function ManageResponsable($source,$event,$on) {
    debug("ENTRANDO EN MANAGE $on","green");
    global $GSPAnel;
    $objResponse = new xajaxResponse();
    
    if ($on>1) {
      $_SESSION[$GSPAnel->id]["responsable"]=$on;
    } else
      $_SESSION[$GSPAnel->id]["responsable"]="";
    
    session_write_close (  );
    
    $objResponse->script("tableGrid_{$GSPAnel->dGrid->id}.refresh()");
    
    $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$GSPAnel->aForms[0]->id}','gtasklog')");
    
    
    
    return $objResponse;
  }
 
  function ManageCheckActivadas($source,$event,$on) {
    debug("ENTRANDO EN MANAGE","green");
    global $GSPAnel;
    $objResponse = new xajaxResponse();
    
    if ($on=="on") {
      $_SESSION[$GSPAnel->id]["showfinished"]=true;
    } else
      $_SESSION[$GSPAnel->id]["showfinished"]=false;
    session_write_close (  );
    
    $objResponse->script("tableGrid_{$GSPAnel->dGrid->id}.refresh()");
    
    $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$GSPAnel->aForms[0]->id}','gtasklog')");
    
    
    
    return $objResponse;
  }
  

  
  
  function ManageCheckManual($source,$event,$on) {
    debug("ENTRANDO EN MANAGE","green");
    global $GSPAnel;
    $objResponse = new xajaxResponse();
    
    if ($on=="on") {
      $_SESSION[$GSPAnel->id]["showOnlyManual"]=true;
    } else
      $_SESSION[$GSPAnel->id]["showOnlyManual"]=false;
    session_write_close (  );
    
    $objResponse->script("tableGrid_{$GSPAnel->dGrid->id}.refresh()");
    
    $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$GSPAnel->aForms[0]->id}','gtasklog')");
    
    return $objResponse;
  }
  
  
  /* 
   * 
   * Condiciones del QUERY
   */
  
  
  $additionalCond=" (estado<>'Terminada') ";
  $GSPAnel=new GSControlPanel($FormWindow,"Registros","gtasklog");
  
  if ($_SESSION[$GSPAnel->id]["showfinished"])  {
    $additionalCond="(((estado<>'Terminada')) OR (estado='Terminada'))";
    $ShowTerminadas->setChecked(true);
  } else
      $ShowTerminadas->setChecked(false);
  
  if ($_SESSION[$GSPAnel->id]["showOnlyManual"])  {
    $additionalCond.=" AND automatica='No'";
    $ShowManuales->setChecked(true);
  } else 
    $ShowManuales->setChecked(false);    

  
  if ($_SESSION[$GSPAnel->id]["responsable"])  {
    $additionalCond.=" AND responsable={$_SESSION[$GSPAnel->id]["responsable"]}";
    $Responsable->setSelectedIndex($_SESSION[$GSPAnel->id]["responsable"]);
  } 
  
  if ($_SESSION[$GSPAnel->id]["etiqueta"])  {
    $additionalCond.=" AND etiqueta LIKE '%{$_SESSION[$GSPAnel->id]["etiqueta"]}%'";
    $Filtroetiqueta->setSelectedValue($_SESSION[$GSPAnel->id]["etiqueta"]);
  }  
      
  debug("$additionalCond","yellow");
  
  $GSPAnel->SQL_CONDS["gtasklog"]="$additionalCond";
    
  
  $GSPAnel->SQL_SORT["gtasklog"]="inicio DESC,estado ASC";
  
  
  /* widget */
  $initiateButton=new wButton("initbutton",$GSPAnel->dForm->buttonPane);
  $initiateButton->label="Iniciar";
  $initiateButton->addListener("onclick","iniciarTarea");
  $initiateButton->Listener["onclick"]->addParameter( XAJAX_FORM_VALUES,$GSPAnel->dForm->id);
  
  $terminateButton=new wButton("terbutton",$GSPAnel->dForm->buttonPane);
  $terminateButton->label="Terminar";
  $terminateButton->addListener("onclick","terminarTarea");
  $terminateButton->Listener["onclick"]->addParameter( XAJAX_FORM_VALUES,$GSPAnel->dForm->id);
  
  $runButton=new wButton("runbutton",$GSPAnel->dForm->buttonPane);
  $runButton->label="Ejecutar";
  $runButton->addListener("onclick","runTarea");
  $runButton->Listener["onclick"]->addParameter( XAJAX_FORM_VALUES,$GSPAnel->dForm->id);
  
  function runTarea($source,$event,$formData) {
    global $GSPAnel;
    $objResponse = new xajaxResponse();
    $task=newObject("gtasklog",$formData["ID"]);
    
    if ($task->ID<2)
      $objResponse->script("alert('Selecciona una tarea primero')");
    else {
      $task->setStatus("ini");
      debug("php5 ".dirname(__FILE__)."/cmd_spawn_task.php {$formData["ID"]}  &>/tmp/task_{$formData["ID"]}.log&","red");
      system("(php5 ".dirname(__FILE__)."/cmd_spawn_task.php {$formData["ID"]} >/tmp/task_{$formData["ID"]}.log) >/dev/null &");
      
      $objResponse->script("tableGrid_{$GSPAnel->dGrid->id}.refresh()");
      $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$GSPAnel->aForms[0]->id}','gtasklog')");
    } 
    
    return $objResponse;
    
  }
  
  function iniciarTarea($source,$event,$formData) {
    global $GSPAnel,$SYS;
    $objResponse = new xajaxResponse();
    $task=newObject("gtasklog",$formData["ID"]);
    
    if ($task->ID<2)
      $objResponse->script("alert('Selecciona una tarea primero')");
    else {
      $task->setStatus("ini");
      $objResponse->assign("statusBox","value","Tarea iniciada");
      $objResponse->script("tableGrid_{$GSPAnel->dGrid->id}.refresh()");
      $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$GSPAnel->aForms[0]->id}','gtasklog')");
    } 
    
    
    return $objResponse;
    
  }
  
  function terminarTarea($source,$event,$formData) {
    global $GSPAnel;
    $objResponse = new xajaxResponse();
    $task=newObject("gtasklog",$formData["ID"]);
    
    if ($task->ID<2)
      $objResponse->script("alert('Selecciona una tarea primero')");
    else {
      if (!$task->setStatus("ter")) {
        $objResponse->script("alert('{$task->ERROR}')");
      }
      else {
        $objResponse->script("tableGrid_{$GSPAnel->dGrid->id}.refresh()");
        $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$GSPAnel->aForms[0]->id}','gtasklog')");
      }
    } 
    
    return $objResponse;
    
  }
  $GSPAnel->formatGridData="myFormatGridData";
  
  function myFormatGridData(&$res) {
    debug("ENTRANDO EN formatGridData","green");
    foreach ($res as $k=>$obj) {
      if (($obj->inicio<time())&&($obj->estado!='Terminada')) {
        $res[$k]->etiqueta="<b style='color:red'>{$obj->etiqueta}</b>";
      }
    }
  };
  
  $GSPAnel->addTab("Pasos","gsteplog","gtasklog_id");
  $initiateButton=new wButton("initbutton2",$GSPAnel->aForms[1]->buttonPane);
  $initiateButton->label="Iniciar";
  $initiateButton->addListener("onclick","iniciarPaso");
  $initiateButton->Listener["onclick"]->addParameter( XAJAX_FORM_VALUES,$GSPAnel->aForms[1]->id);
  $initiateButton=new wButton("closebutton2",$GSPAnel->aForms[1]->buttonPane);
  $initiateButton->label="Finalizar";
  $initiateButton->addListener("onclick","cerrarPaso");
  $initiateButton->Listener["onclick"]->addParameter( XAJAX_FORM_VALUES,$GSPAnel->aForms[1]->id);
  
  $initiateButton=new wButton("runButton",$GSPAnel->aForms[1]->buttonPane);
  $initiateButton->label="Ejecutar";
  $initiateButton->addListener("onclick","ejecutarPaso");
  $initiateButton->Listener["onclick"]->addParameter( XAJAX_FORM_VALUES,$GSPAnel->aForms[1]->id);
  
  $checkStatus=new wButton("checkstatus",$GSPAnel->dForm->buttonPane);
  $checkStatus->label="Estado";
  $checkStatus->addListener("onclick","checkStatus");
  $checkStatus->Listener["onclick"]->addParameter( XAJAX_FORM_VALUES,$GSPAnel->dForm->id);
  
  
  /* Ejecutar Paso */
  
  function ejecutarPaso($source,$event,$formData) {
    global $GSPAnel;
    $objResponse = new xajaxResponse();
    $task=newObject("gsteplog",$formData["ID"]);
    
    if ($task->ID<2)
      $objResponse->script("alert('Selecciona una tarea primero')");
    else {
      $MSG=$task->run();
      $objResponse->assign("statusBox","value","Paso llamado a ejecucion ( {$task->ERROR} )");
      $objResponse->script("CallAllHandlers()");
      $objResponse->script("$('{$GSPAnel->aForms[1]->id}').reset()");
      $objResponse->script("xajax_wForm.requestloadFromId({$task->ID},'{$GSPAnel->aForms[1]->id}','gsteplog')");
    } 
    
    return $objResponse;
    
  }
  
  function iniciarPaso($source,$event,$formData) {
    global $GSPAnel;
    $objResponse = new xajaxResponse();
    $task=newObject("gsteplog",$formData["ID"]);
    
    if ($task->ID<2)
      $objResponse->script("alert('Selecciona una tarea primero')");
    else {
      $task->setStatus("ini");
      $objResponse->assign("statusBox","value","Paso iniciado");
      $objResponse->script("tableGrid_{$GSPAnel->grids["gsteplog"]->id}.refresh()");
      $objResponse->script("$('{$GSPAnel->aForms[1]->id}').reset()");
      
    } 
    
    
    
    return $objResponse;
    
  }
  
  function cerrarPaso($source,$event,$formData) {
    global $GSPAnel;
    $objResponse = new xajaxResponse();
    $task=newObject("gsteplog",$formData["ID"]);
    
    if ($task->ID<2)
      $objResponse->script("alert('Selecciona una tarea primero')");
    else {
      $task->setStatus("ter");
      $objResponse->assign("statusBox","value","Paso iniciado");
      $objResponse->script("tableGrid_{$GSPAnel->grids["gsteplog"]->id}.refresh()");
      $objResponse->script("$('{$GSPAnel->aForms[1]->id}').reset()");
    } 
    
    return $objResponse;
    
  }
  
  function checkStatus($source,$event,$formData) {
    global $GSPAnel;
    $objResponse = new xajaxResponse();
    if ($formData["estado"]=="No Iniciada")
      return $objResponse;
    $logData="<pre>".file_get_contents("/tmp/task_{$formData["ID"]}.log")."</pre>";
    $objResponse->script('
      logWin=window.open();
      logWin.document.writeln(base64_decode("'.base64_encode($logData).'"));
      ');
    
    return $objResponse;
    
  }
  //$GSPAnel2=new GSControlPanel($LayOut,"gsteplog");
  
  if ((empty($_POST))&&(!$_GET["oDataRequest"])){
    
    $FormWindow->render();
    //$window->render();
    $xajax->printJavascript($SYS["ROOT"]."/Framework/Extensions/xajax");
    
  } else if ($_GET["oDataRequest"]) {
    /* Data Request */
    /*$classname=$_GET["oDataRequest"];
    $o=new $classname(null,$_GET["instance"]);*/
    $GSPAnel->aj_RequestData($_GET["instance"]);
  }
  else {
    $xajax->processRequest();
    debug("End","red");
  }
  
  ?>
