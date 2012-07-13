<?php
  
  function getByTaskDate($gtask,$ts) {
    
    $res=$this->select("gtask_id={$gtask->ID} AND inicio=$ts");
    return(current($res));
    
  }
  
  function idname() {
    
    if ($this->gtask_id)
      return $this->resolve("gtask_id")."@".int_to_text($this->inicio);
    else
      return "-"; 
  }
  
  function save()
  {
    global $prefix;
    debug("Info: Calling Extended save");
    $old=newObject("gtasklog",$this->ID);
    if ($this->inicio<3600) {
      $this->inicio=time();
      
    }
    if (($old->estado=='No Iniciada')&&($this->estado=='En Curso')) {
      // Accion implicita de iniciar tarea.
      debug("Info: Creando pasos","white");
      $s=newObject("gstep");
      $steps=$s->listAll("ID",false,"gtask_id={$this->gtask_id} and activo='Si'",0,"secuencia ASC");
      foreach ($steps as $sid=>$ssid) {
        $fechadeevaluacion=($this->inicio>3600)?($this->inicio+($this->diasderetraso*24*60*60)):($this->inicio+($this->diasderetraso*24*60*60));
        $origstep=newObject("gstep",$sid);
        $step=newObject("gsteplog");
        $step->setAll($origstep->properties);
        $step->ID=0;
        $step->gtask_id=$this->gtask_id;
        $step->gstep_id=$sid;
        $step->gtasklog_id=$this->ID;
        $step->secuencia=$origstep->secuencia;
        $step->comando=evaluateExpression($origstep->comando,$fechadeevaluacion,true);
        $step->script=evaluateExpression($origstep->script,$fechadeevaluacion,false);
        $step->ficheros=evaluateExpression($origstep->ficheros,$fechadeevaluacion,true);

        
        $step->estado='No Iniciada';
        $step->maxreturnstatus=$origstep->maxreturnstatus;
        
        
        
        $step->save();
      }
    }
    
    if (($old->estado=='En Curso')&&($this->estado=='Cancelada')) {
      
      $this->enviarAviso("cancelada");
      
    }
    
    $this->ejecutor=(BILO_uid()>1)?BILO_uid():$this->ejecutor;
    $par = new Ente($this->name);
    $par = typecast($this, "Ente");
    //dataDump($par);
    return $par->save();
  }
  
  function SetStatus($status) {
    
    
    if ($status=="ini") {
      $this->estado="En Curso"; 
      $this->rini=time();
    } else if ($status=="can") {
      $this->estado="Cancelada";  
    } else if ($status=="ter") {
      
      
      $s=newObject("gsteplog");
      $steps=$s->select("gtasklog_id={$this->ID} AND estado<>'Terminada'");
      if ($s->nRes>0) {
        $this->ERROR="Existen pasos no terminados";
        return false;
      }
      $this->estado="Terminada";  
      $this->fin=time();
      
      if ($this->emailconfirmacion=='Si') 
        $this->enviarAviso();
      
      
    }
    if($this->save()) {
      return $this->ID;
    }
    else
      return false;
    
  }
  
  
  function delete() {
    
    $o=newObject("gsteplog");
    $o->deletes("gtasklog_id={$this->ID}");
    $par = new Ente($this->name);
    $par = typecast($this, "Ente");
    
    return $par->delete();
    
  }
  
  function enviarAviso($status="exito") {
    global $SYS;
    
    require_once("Lib/lib_phpmailer.php");  
    $mail = new PHPMailer();
    $g=newObject("group",$this->departamento);
    if ($g->ID<2)
      return;
    // Recojer responsables
    foreach ($g->listUsersInGroup() as $user) {
      if (empty($user->email))
        continue;
      else 
        $mail->AddAddress($user->email);
    }
    
    $mail->IsHTML(true);
    $mail->From = $SYS["admin_email"];
    $mail->FromName = utf8_decode($SYS["admin_realm"]);
    $mail->Subject = utf8_decode("La tarea {$this->etiqueta} ha sido ejecutada ($status)");
    
    $mail->Body = utf8_decode("
      <h3>La tarea {$this->etiqueta} ha sido ejecutada. El estado de salida ha sido '$status'.</h3><br>
      <table width='500' border='1'>
      <tr><td>Ejecución prevista</td><td> ".strftime("%d/%m/%Y a las %H:%M",$this->inicio)."</td></tr>
      <tr><td>Ejecución real</td><td> ".strftime("%d/%m/%Y a las %H:%M",$this->rini)."</td></tr>
      <tr><td>Fin de tarea</td><td> ".strftime("%d/%m/%Y a las %H:%M",$this->fin)."</td></tr></table>
      <hr>
      
      ");
    
    $s=newObject("gsteplog");
    $steps=$s->listAll("ID",false,"gtasklog_id={$this->ID}",0,"secuencia ASC");
    foreach ($steps as $sid=>$ssid) {
      
      $origstep=newObject("gsteplog",$sid);
      if (($origstep->fin+$origstep->rini)<10000)
        continue;
      
      $mail->Body.=utf8_decode("
        <div> Paso {$origstep->secuencia}: ".strftime("%d/%m/%Y a las %H:%M:%S",$origstep->rini)."<br>
        {$origstep->ultimoerror}<br>
        Fin: .".strftime("%d/%m/%Y a las %H:%M:%S",$origstep->fin)."</div>");
    }
    $mail->Body.="<br>Un saludo.";
    
    if ($mail->Send()) 
      $this->notas.="Email enviado. ".strftime("%d/%m/%Y %H:%M").".";
    else
      $this->notas.="Email NO enviado. ".strftime("%d/%m/%Y %H:%M").".";
    
    
    
    
    
    
  }
  ?>

