<?php
  
  require_once("coreg2.php");
  $SYS["PROJECT"]="GlobalSche";
  
  set_include_dir(dirname(__FILE__)."/local/Tmpl/-");
  set_include_dir(dirname(__FILE__)."/-");
  set_include_dir(dirname(__FILE__)."/../Memo/-");
  set_include_dir(dirname(__FILE__)."/../../Framework/Extensions/xajax/-");
  
  define("TASK_TIMEOUT",60);
  
  function generateJobsToday() {
    setlimitRows(150000);
    $d=newObject("gtasklog");
    //$d->deletes("estado='No iniciada' AND inicio>".dateTodayStamp());
    $u=newObject("gtask");
    $ids=$u->allID();
    $d=array();
    $tasks=array(1);
    
    foreach ($ids as $id) {
      $o=newObject("gtask",$id);
      if ($o->activa!='Si')
         continue;
      $cron = new Parser($o->getCronString());
      $TSTAMP=time();
      $RunsToday=$cron->getRuns($TSTAMP);
     
      
      foreach ($RunsToday as $timeStampOfRun){
        //echo strftime("%d/%m/%Y %H:%M",$cron_ran)." # ".$cron_ran;
        $tl=newObject("gtasklog");  
        $tasklog=$tl->getByTaskDate($o,$timeStampOfRun);
        if ($tasklog) {
          $tasks[]=$tasklog->ID;
          //print_r($tasklog);
        } else {
          //
          $tl=newObject("gtasklog");
          $tl->etiqueta=$o->titulo."@".strftime("%Y%m%d",$TSTAMP);
          $tl->tipo='Desde Definición';
          $tl->gtask_id=$id;
          $tl->schedule_id=$o->schedule_id;
          $tl->inicio=$timeStampOfRun;
          $tl->estado='No iniciada';
          $tl->automatica=$o->automatica;
          $tl->emailconfirmacion=$o->emailconfirmacion;
          $tl->departamento=$o->departamento;
          $tl->responsable=$o->responsable;
          $tl->diasderetraso=$o->diasderetraso;
          $tasks[]=$tl->save();
          
        }
      }
      
    }
    resetlimitRows();
  }
  
  function lastMonth($timestamp) {
      
      $newDate=strtotime(date("m",$timestamp)."/1/".date("Y",$timestamp)." -1 day");
      return $newDate;
  }
  
  function evaluateExpression($expression,$timestamp,$withSTRFTIME=false) {
      $currentLocale=setlocale(LC_ALL,0);

      setlocale(LC_ALL,'es_ES.UTF-8');
      /* Replacementes en español */
      $buffer=preg_replace("/__FECHA{([^\{]{1,100}?)}/e",'strftime("$1",'.$timestamp.')',$expression);
      $buffer=preg_replace("/__ULTIMODIAMESPASADO{([^\{]{1,100}?)}/e", 'strftime("$1",' . lastMonth($timestamp) . ')', $buffer);
      
      setlocale(LC_ALL,$currentLocale);
      /* Replacementes en guiri */
      $buffer=preg_replace("/__FECHA_AME{([^\{]{1,100}?)}/e",'strftime("$1",'.$timestamp.')',$buffer);
      $buffer=preg_replace("/__ULTIMODIAMESPASADO_AME{([^\{]{1,100}?)}/e", 'strftime("$1",' . lastMonth($timestamp) . ')', $buffer);
      if ($withSTRFTIME) {
              $buffer=strftime($buffer,$timestamp);
      }
      
      return $buffer;
  }
  ?>
