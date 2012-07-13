<?php
  
  $ORIGPATH=ini_get("include_path");
  $PATH=dirname(__FILE__)."/../../";
  ini_set("include_path","$ORIGPATH:$PATH:$PATH/Framework:$PATH/Apps");
  $TrazaStatus=false;
  error_reporting(E_ALL);
  
  require("GlobalSche.php");
  
  $o=newObject("gtasklog");
  setlimitRows(150);
  $o->searchResults=$o->select("floor(inicio/60)=floor(UNIX_TIMESTAMP(NOW())/60) AND estado='No Iniciada'");
  //print_r($o->searchResults);
  foreach ($o->searchResults as $ID=>$GTASK) {
    echo "Comprobando {$GTASK->ID} '{$GTASK->idname()}' Auto=#{$GTASK->automatica}#\n";
    if ($GTASK->automatica!='Si')
      continue;
    echo "Actualizando estado \n";
    $GTASK->setStatus("ini");
    /* Must spawn a worker here */
    $PID=$GTASK->ID;
    echo "Ejecutando  pasos {$GTASK->ID} '{$GTASK->idname()}'\n";
    $cmd="php5 ".dirname(__FILE__)."/cmd_spawn_task.php $PID 2>&1 >/tmp/task_$PID.log & ";
    pclose(popen($cmd,"r"));
    
    echo "Worker spawned $cmd\n";
  }
  
  
  ?>