<?php
  
  require_once("GlobalSche/GlobalSche.php");
  require_once("JasperReports/JasperReports.php");
  require_once("JasperReports/util_jasperreport.php");
  
  $parms=array(
    array("type"=>"date","value"=>time()
         )
  );
  
  callReport(dirname(__FILE__)."/../local/Reports/TareasAtrasadas.jrxml",$parms);
  
  
  
  
  
  ?>
