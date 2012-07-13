<?php
ini_set("max_execution_time","600");
require_once("System.php");
if (ini_get("output_buffering"))
	 ob_end_flush();
plantHTML(array(),"f_menu");
$errors=0;
$success=0;
$total=filesize($_FILES["fichero_sql"]["tmp_name"]);
echo "<h3 align=\"center\">Importando datos....espere..</h3>";
$fd = fopen ($_FILES["fichero_sql"]["tmp_name"], "r");
  while (!feof($fd)) {
      $buffer.= fgets($fd, 8192);
      
      $sbuffer=trim($buffer);
      if (substr($sbuffer,strlen($sbuffer)-1,1)==";") {
            
      	    /**********  UTF-8 PATCH */
	    
	    $bufferd=utf8_decode($buffer);
	    $buffer=$bufferd;
		
	    /**********  UTF-8 PATCH */
	    
	    if (_query($buffer))
			$success++;
		else
			$errors++;
	    
	    $partial+=(strlen($buffer));
	    unset($buffer);
	    $count=0;
	    
      }
      else  {
      	    //echo "<pre>@".$sbuffer."@</pre>";
	    $count+=8192;
      }
      
      if ($count>(8192*512))
      	break;
  $p=round($partial/$total*100);
  if (($p!=$oldp)&&(($p%10)==0)) {
		jsAction("setProgress('$p');");
		flush();
	}
	$oldp=$p;
  flush();
  }
  fclose ($fd);
echo "<h2 align=\"center\">$success instrucciones ejecutadas :: $errors errores</h2>";
jsAction("setProgress('0');");
HTML("footer");
?>