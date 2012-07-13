<?php
  require(dirname(__FILE__)."/controller_base_js.php");
  
  try {
    $data=utf8_encode($_POST["code"]);
    $f=fopen(dirname(__FILE__)."/../".trim($file),"wt");
    if (!$f) {
      $textarea=new jsO("msgbox","parent",false);
      $textarea->jsO->value="Error al guardar $file";
      $textarea->jsMapO();
      $textarea->jsMapM();
      jsCodeFlush();
      die();
      
    }
    fwrite($f,$data,strlen($data));
    fclose($f);
  } catch (Exception $e) {
    jsCodePush("\n//alert('$e');\n");
    $textarea=new jsO("msgbox","parent",false);
    $textarea->jsO->value="Error al guardar $file ($e)";
    $textarea->jsMapO();
    $textarea->jsMapM();
    jsCodeFlush();
    die();
    
  }
  debug(($data),"red");
  $textarea=new jsO("msgbox","parent",false);
  $textarea->jsO->value="$file guardado (".strftime("%H:%M:%S").")";
  $textarea->jsMapO();
  $textarea->jsMapM();
  
  jsCodeFlush();
  
  
  
  ?>

