<?php
  
  $ORIGPATH=ini_get("include_path");
  $PATH=dirname(__FILE__)."/../../";
  ini_set("include_path","$ORIGPATH:$PATH:$PATH/Framework:$PATH/Apps");
  $TrazaStatus=false;
  error_reporting(E_ALL);
  
  require("GlobalSche.php");
  require_once("../JasperReports/JasperReports.php");
  require_once("../JasperReports/util_jasperreport.php");
  
  $parms=array(
    array("type"=>"date","value"=>time()
         )
  );
  
  
  $filename=callReport(dirname(__FILE__)."/local/Reports/InformeSemanal.jrxml",$parms,false);
   
  require_once("Lib/lib_phpmailer.php");
  $mail = new PHPMailer();
  $g=newObject("group",4);
  foreach ($g->listUsersInGroup() as $user) {
    if (empty($user->email))
      continue;
    else
      $mail->AddAddress($user->email);
  }
  $mail->IsHTML(true);
  $mail->From = $SYS["admin_email"];
  $mail->FromName = utf8_decode($SYS["admin_realm"]);
  $mail->Subject = utf8_decode("Informe Semanal");
  if (!$mail->AddAttachment($filename,basename($filename),"base64", "application/pdf"))
    die($mail->ErrorInfo." ($filename) \n");
  if (!$mail->Send())
    die("Error sending".$mail->ErrorInfo."\n");
  else
    echo _("Aviso enviado\n");
  
  
  
  
  ?>