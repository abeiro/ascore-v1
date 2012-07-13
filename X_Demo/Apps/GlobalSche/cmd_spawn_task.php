<?php
  
  
  $ORIGPATH=ini_get("include_path");
  $PATH=dirname(__FILE__)."/../../";
  ini_set("include_path","$ORIGPATH:$PATH:$PATH/Framework:$PATH/Apps");
  $TrazaStatus=false;
  error_reporting(E_ERROR);
  
  require("GlobalSche.php");
  setlimitRows(150);
  
  
  $t=newObject("gtasklog",$argv[1]);
  $s=newObject("gsteplog");
  $s->searchResults=$s->select("gtasklog_id={$argv[1]}",0,"secuencia ASC");
  foreach ($s->searchResults as $step) {
    echo "#####################################################################################\n\n";
    echo strftime("%d-%m-%Y %H:%M:%S\t").$step->resolve("gtasklog_id")."\tPaso: ".$step->resolve("gstep_id")."\t{$step->estado}\n";
    flush();
    $step->run();
    
    echo strftime("%d-%m-%Y %H:%M:%S\t").$step->resolve("gtasklog_id")."\tPaso: ".$step->resolve("gstep_id")."\t{$step->estado}\n";
    
    if ($step->estado=='Cancelada') {
      $t->anotaciones=$step->ERROR." ".$step->TMPOUT;
      $t->setStatus("can");

      break;
    }
    
  }
  
  ?>