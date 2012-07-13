<?php
  
  die("Desactivado");
  require_once("GlobalSche/GlobalSche.php");
  require_once("GlobalSche/CronParser.class.php");
  
  $u=newObject("gtask");
  $ids=$u->allID();
  $d=array();
  $tasks=array(1);
  
  //generateJobsToday();
  
  foreach ($ids as $id) {
    $o=newObject("gtask",$id);
    $cron = new Parser($o->getCronString());
    $RunsToday=$cron->getRuns(time());
    
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
        $tl->gtask_id=$id;
        $tl->schedule_id=$o->schedule_id;
        $tl->inicio=$timeStampOfRun;
        $tl->estado='No iniciada';
        $tasks[]=$tl->save();
        
      }
    }
    
  }
  $o=newObject("gtasklog");
  $d=array(
    "gtask_id"=>$o->get_external_reference("gtask_id"),
    "schedule_id"=>$o->get_external_reference("schedule_id")
  );
  $o->searchResults=$o->select("(inicio >".dateTodayStamp()." ) OR (estado='No iniciada')",$offset,$sort);
  listList($o,$d,"gtasklog_control");
  
  
  
  
  ?>

