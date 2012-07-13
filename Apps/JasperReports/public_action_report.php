<?php

require_once("JasperReports.php");
HTML("action_header");
if (empty($cID)) {
	jsAction('alert("Por favor, seleccione un elemento")');
	die();


}
$oClass=newObject("$CL",$cID);
$jReport=newObject("jasperreport");
debug("Clase objetivo $CL con ID $cID","red");
setLimitRows(15000);
$jReport->searchResults=$jReport->select("class='$CL'");
resetLimitRows();
debug("Buscando reporte para $CL","green");

/* Jasperreport initialization */
include("phpjasper/Java.inc");
java_require(dirname(__FILE__)."/phpjasper/drivers.jar"); 
$dir=dirname(__FILE__)."/phpjasper/Pool/";


if (is_object($jReport->searchResults[0])) {

	$crRep=$jReport->searchResults[0];
	$informe =  $crRep->filename;
	$URLBASE=$SYS["BASE"]."/Apps/JasperReports//phpjasper/Pool/";
	debug("{$URLBASE}{$informe}.jrxml","red");

	/* Extracción de parámetros */
	$crParms=newObject("reportparams");
	setLimitRows(15000);
	$crParms->searchResults=$crParms->select("jasperreport_id={$crRep->ID}");
	resetLimitRows();
	foreach ($crParms->searchResults as $parID=>$parVal) {
		$atomParam=array();
		$atomParam["paraname"]=$parVal->paraname;
		$atomParam["paratype"]=$parVal->paratype;
		$atomParam["value"]=(empty($oClass->properties[$parVal->paraname]))?$parVal->defaulty:$oClass->properties[$parVal->paraname];
		debug("Valor de {$parVal->paraname} => ".$oClass->properties[$parVal->paraname]." ( {$parVal->paratype} )","green");
		$P[]=$atomParam;
	
	}
	
	$jcm = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");  
	$report = $jcm->compileReport($URLBASE.$informe.".jrxml");  
	
	try {
		$jfm = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");  
			$Conn = new Java("org.altic.jasperReports.JdbcConnection");
			$Conn->setDriver("com.mysql.jdbc.Driver");
			$Conn->setConnectString("jdbc:mysql://localhost:3306/{$SYS["mysql"]["DBNAME"]}");			
			$Conn->setUser($SYS["mysql"]["DBUSER"]);
			$Conn->setPassword($SYS["mysql"]["DBPASS"]);
			if ($Conn->getConnection()) {	
				$parameters=new Java("java.util.HashMap");
	
				foreach ($P as $k=>$v) {
					if ($v["paratype"]=="Fecha") {
						$ts1=$v["value"];
						$JAVA_PAR=new Java("java.util.Date",date("y",$ts1)+100,date("m",$ts1)-1,date("d",$ts1));
						$parameters->put($v["paraname"],$JAVA_PAR);
					} else if ($v["paratype"]=="Entero") {
						$JAVA_PAR=new Java("java.lang.Integer",(int)$v["value"]);
						$parameters->put($v["paraname"],$JAVA_PAR);
						$parameters->put($v["paraname"],$JAVA_PAR);
				
					} else if ($v["paratype"]=="Cadena") {
						$JAVA_PAR=new Java("java.lang.String",$v["value"]);
						$parameters->put($v["paraname"],$JAVA_PAR);
					}
					
					
					
				}
								
				$parameters->put("REPORT_DIR",$URLBASE);
					
				$print = $jfm->fillReport($report,$parameters,$Conn->getConnection());  
				
				$filem=time();
				$finalname=session_save_path()."/coreg2_cache/{$SYS["ASCACHEDIR"]}/$filem.pdf";
						
				$jem = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");  
				$jem->exportReportToPdfFile($print, $finalname);  
				//echo $jem->exportReportToPdf($print);  
				ob_end_clean();
				if (file_exists($finalname)) {
					header("Content-Type: application/pdf");
					header("Content-Disposition: inline; filename=\"$informe.pdf\"");
					readfile($finalname);
					unlink($finalname);
				}
			} else {
				
				echo "Errors";
				
			}
			
			} catch (JavaException $ex) {
							$trace = new Java("java.io.ByteArrayOutputStream");
							$ex->printStackTrace(new Java("java.io.PrintStream", $trace));
							debug("java stack trace: $trace\n","red");
							ob_end_clean();
							echo "Error ";
						}
						
	
	
	
} else {

jsAction('alert("Este formulario no tiene informe asociado")');


}
