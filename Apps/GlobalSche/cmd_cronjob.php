<?php
  
  
  $ORIGPATH=ini_get("include_path");
  $PATH=dirname(__FILE__)."/../../";
  ini_set("include_path","$ORIGPATH:$PATH:$PATH/Framework:$PATH/Apps");
  $TrazaStatus=false;
  error_reporting(E_ERROR);
  
  
  require_once("GlobalSche/GlobalSche.php");
  require_once("GlobalSche/CronParser.class.php");
  
  
  
  generateJobsToday();
  
  
  ?>
