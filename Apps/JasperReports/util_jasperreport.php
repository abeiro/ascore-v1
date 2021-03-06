<?php
  
  
  function callReport($nomReport,$arrParam,$sendData=true)
  {
    global $SYS;
    require_once("Java.inc");
    
    //java_require(dirname(__FILE__)."/jfreechart-1.0.1.jar");
    java_require(dirname(__FILE__)."/phpjasper/drivers.jar");
    //java_require("/usr/share/java/postgresql-jdbc3.jar");
    
    try {
      $tmpReport=basename($nomReport);
      copy("$nomReport","/tmp/$tmpReport.jrxml");
      
      $jcm=new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
      $report=$jcm->compileReport("/tmp/$tmpReport.jrxml");
      
      $jfm=new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
      
      $Conn=new Java("org.altic.jasperReports.JdbcConnection");
      //$Conn->setDriver("org.postgresql.Driver");
      
      
      $Conn->setDriver("com.mysql.jdbc.Driver");
      
      $Conn->setConnectString("jdbc:".$SYS["DBDRIVER"]."://".
                                           $SYS[$SYS["DBDRIVER"]]["DBHOST"]."/".
                                                                  $SYS[$SYS["DBDRIVER"]]["DBNAME"]);
      // Parche tonto
      //     $Conn->setConnectString("jdbc:postgresql://localhost:5432/{$_SESSION['dbname']}"); //
      
      $Conn->setUser($SYS[$SYS["DBDRIVER"]]["DBUSER"]);
      $Conn->setPassword($SYS[$SYS["DBDRIVER"]]["DBPASS"]);
      
      if ($Conn->getConnection()) {
        
        $parameters=new Java("java.util.HashMap");
        
        /* PARAMETROS */
        foreach ($arrParam as $n=>$v) 
                 {
                   // Tendré que comprobar el tipo, pasar ad_reference_id por parámetro
                   if(isset($v['type']))
                   {
                     if ($v['type'] == "Date") {
                       $ts1=text_to_int($v['value']);
                       $JAVA_PAR=new Java("java.util.Date",date("Y",$ts1)-1900,date("m",$ts1)-1,date("d",$ts1));
                       $parameters->put($n,$JAVA_PAR);
                       
                     } else if ($v['type'] == "Integer") {
                       $JAVA_PAR=new Java("java.lang.Integer",$v['value']);
                       //die( "NOMBRE PARAM: $n ; VALOR: {$v['value']} <br />");
                       $parameters->put($n,$JAVA_PAR);
                       
                     } else if ($v['type'] == "Boolean") {
                       $JAVA_PAR=new Java("java.lang.Boolean",$v['value']);
                       $parameters->put($n,$JAVA_PAR);
                       
                     } else if ($v['type'] == "Double") {
                       $JAVA_PAR=new Java("java.lang.Double",$v['value']);
                       $parameters->put($n,$JAVA_PAR);
                     }else 
                       $parameters->put($n,$v['value']);
                   }else {
                     $parameters->put($n,$v['value']);
                   }
                 }
        
        $parameters->put("REPORT_DIR","/tmp/");
        
        
        $print=$jfm->fillReport($report,$parameters,$Conn->getConnection());
        
        $listaPag = $print->getPages();
        $numPag = $listaPag->size();
        
        if($numPag == '0')
        {
          // Mostramos mensaje, el documento no contiene páginas (Usar div similar al session caducada)
          $_SESSION['msgDocInfo'] = "El documento no tiene p&aacute;ginas.";
        }else{
          $filem=time();
          
          $finalname="/tmp/$filem.pdf";
          $jem=new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
          $jem->exportReportToPdfFile($print, $finalname);
          
          while(ob_end_clean());
          
          if (file_exists($finalname)) {
            
            if ($sendData) {
              header('Content-type: application/pdf');
              header("Content-Disposition: inline; filename=\"$finalname\"");
              readfile($finalname);
            } else {
              return($finalname);
              
            }
            
          }
          
          die();
        }
        
      } else
        echo "ERRORS";
      
    } catch (JavaException $ex) {
      /*$trace = new Java("java.io.ByteArrayOutputStream");
      $ex->printStackTrace(new Java("java.io.PrintStream", $trace));
      print "java stack trace: $trace\n";*/
      
      ob_end_clean();
      echo "<pre>ERROR<br>";
      echo "Cause: ".$ex->getCause()."<br>";
      echo "Message: ".$ex->getMessage()."</pre>";
    }
  }
  
  
  //callReport(0,0);
  ?>
