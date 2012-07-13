<?php
  
  $ORIGPATH=ini_get("include_path");
  $PATH=dirname(__FILE__)."/../../";
  ini_set("include_path","$ORIGPATH:$PATH:$PATH/Framework:$PATH/Apps");
  $TrazaStatus=false;
  error_reporting(E_ALL);
  
  require("GlobalSche.php");
  
  $o=newObject("gtasklog");
  setlimitRows(150);
  $o->searchResults=$o->select("inicio<UNIX_TIMESTAMP(NOW()) AND estado='No Iniciada'");
  //print_r($o->searchResults);
  
  /*require_once("Lib/lib_google_translate.php");
  $gt = new Gtranslate;
  $data=$gt->english_to_spanish("hello world");
  $excuses = file($SYS["DOCROOT"]."../Data/excuses.txt");
  $tdata=$gt->english_to_spanish($excuses[rand(0,sizeof($excuses)-1)]);
  */
  
  require_once("Lib/lib_class_xml_to_array.php");
  $qod=new XmlToArray(file_get_contents("http://chistes.developers4web.com/rss.php"));
  $arr=$qod->createArray();
  $chiste=strip_tags (strtr($arr["rss"]["channel"]["item"]["description"],array("/"=>"<br>")),"<br><br/><p><div><a>");
  
  $SYS["ROOT"]="http://172.24.81.52/globalscheduler/Backend/";
  
  foreach ($o->searchResults as $ID=>$GTASK) {
    $g=newObject("group",$GTASK->departamento);
    // Recojer responsables
    foreach ($g->listUsersInGroup() as $user) {
      if (empty($user->email))
        continue;
      echo $GTASK->etiqueta." ".$user->email."\n";
      require_once("Lib/lib_phpmailer.php");
      $mail = new PHPMailer();
      $mail->AddAddress($user->email);
      $mail->IsHTML(true);
      $mail->From = $SYS["admin_email"];
      $mail->FromName = utf8_decode($SYS["admin_realm"]);
      $mail->Subject = utf8_decode("La tarea {$GTASK->etiqueta} está retrasada");
      
      $mail->Body = utf8_decode("
        La tarea {$GTASK->idname()}  ({$GTASK->etiqueta})  está retrasada.<br>
        Su fecha y hora prevista es ".strftime("%d/%m/%Y a las %H:%M",$GTASK->inicio)."
        <br>
        Puede revisar esta tarea accediendo a la aplicación: <a href='{$SYS["ROOT"]}'>{$SYS["ROOT"]}</
        a>
        
        <hr>
        El sistema le propone mejorar su humor:
        $chiste   
        
        <br>Un saludo.
        ");
      if (!$mail->Send())
        echo $mail->ErrorInfo();
      else
        echo _("Aviso enviado\n");
      
    }
    
  }
  
  
  ?>
