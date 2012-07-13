<?php

error_reporting(E_ERROR);
require_once("JasperReports.php");

// Llamada desde 'Apps/Informes/admin_menu_entry.php'


if(isset($_GET["informe"]) && $_GET["informe"]=="arbolea_municipio") {
	$informe=$_GET["informe"];
	$P=array();
	$P[0]["paraname"]="mes";
	$P[0]["paratype"]="Entero";
	$P[0]["defaulty"]=$_GET["mes"];
	$P[1]["paraname"]="anio";
	$P[1]["paratype"]="Entero";
	$P[1]["defaulty"]=$_GET["anio"];

// Llamada desde el botón 'Llamar' que existe en la gestión de informes Jasper
} else {

	if(isset($_GET["informe"]) && $_GET["informe"]=="arbolea_usuario") {
	$informe=$_GET["informe"];
	$P=array();
	$P[0]["paraname"]="mes";
	$P[0]["paratype"]="Entero";
	$P[0]["defaulty"]=$_GET["mes"];
	$P[1]["paraname"]="anio";
	$P[1]["paratype"]="Entero";
	$P[1]["defaulty"]=$_GET["anio"];
	$P[2]["paraname"]="id_usuario";
	$P[2]["paratype"]="Entero";
	$P[2]["defaulty"]=$_GET["id_usuario"];
	}
	else{
		if(isset($_GET["informe"]) && $_GET["informe"]=="arbolea_usuarioinf") {
		$informe=$_GET["informe"];
		$P=array();
		$P[0]["paraname"]="ID";
		$P[0]["paratype"]="Entero";
		$P[0]["defaulty"]=$_GET["ID"];
		}
		else{
			if(isset($_GET["informe"]) && $_GET["informe"]=="arbolea_usuarioinc") {
			$informe=$_GET["informe"];
			$P=array();
			$P[0]["paraname"]="mes";
			$P[0]["paratype"]="Entero";
			$P[0]["defaulty"]=$_GET["mes"];
			$P[1]["paraname"]="anio";
			$P[1]["paratype"]="Entero";
			$P[1]["defaulty"]=$_GET["anio"];
			$P[2]["paraname"]="id_usuario";
			$P[2]["paratype"]="Entero";
			$P[2]["defaulty"]=$_GET["id_usuario"];
			}
			else{
				if(isset($_GET["informe"]) && $_GET["informe"]=="Factura") {
				$informe=$_GET["informe"];
				$P=array();
				$P[0]["paraname"]="mes";
				$P[0]["paratype"]="Entero";
				$P[0]["defaulty"]=$_GET["mes"];
				$P[1]["paraname"]="anio";
				$P[1]["paratype"]="Entero";
				$P[1]["defaulty"]=$_GET["anio"];
// 				$P[2]["paraname"]="id_cliente";
// 				$P[2]["paratype"]="Entero";
// 				$P[2]["defaulty"]=$_GET["id_cliente"];
				$P[2]["paraname"]="impuesto";
				$P[2]["paratype"]="Entero";
				$P[2]["defaulty"]=$_GET["impuesto"];
				$P[3]["paraname"]="poblacion";
				$P[3]["paratype"]="Cadena";
				$P[3]["defaulty"]=$_GET["poblacion"];
				$P[4]["paraname"]="precio";
				$P[4]["paratype"]="Decimal";
				$P[4]["defaulty"]=$_GET["precio"];
				$P[5]["paraname"]="numero";
				$P[5]["paratype"]="Entero";
				$P[5]["defaulty"]=$_GET["numero"];
				}
				else{
					if(isset($_GET["informe"]) && $_GET["informe"]=="arbolea_planificacion") {
					$informe=$_GET["informe"];
					$P=array();
					$P[0]["paraname"]="mes";
					$P[0]["paratype"]="Entero";
					$P[0]["defaulty"]=$_GET["mes"];
					$P[1]["paraname"]="anio";
					$P[1]["paratype"]="Entero";
					$P[1]["defaulty"]=$_GET["anio"];
					$P[2]["paraname"]="id_auxiliar";
					$P[2]["paratype"]="Entero";
					$P[2]["defaulty"]=$_GET["id_auxiliar"];
					}
					else{
					$dreport=newObject("jasperreport",$ID);
					$paras=newObject("reportparams");
					$P=$paras->selectA("jasperreport_id=$ID");
					$informe=$dreport->filename;
					}		
				}	
			}
		}
	}
}

include("phpjasper/Java.inc");
java_require(dirname(__FILE__)."/phpjasper/drivers.jar");

$dir=dirname(__FILE__)."/phpjasper/Pool/";

$URLBASE=$SYS["ROOT"]."/Apps/JasperReports/phpjasper/";
/*
try {*/
	
	$jcm=new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
	$report=$jcm->compileReport($dir.$informe.".jrxml");
	$jfm=new JavaClass("net.sf.jasperreports.engine.JasperFillManager");

	$Conn=new Java("org.altic.jasperReports.JdbcConnection");
	$Conn->setDriver("com.mysql.jdbc.Driver");
	
	//$Conn->setConnectString("jdbc:mysql://{$_SERVER["SERVER_NAME"]}:3306/{$SYS["mysql"]["DBNAME"]}");
	// Parche tonto
	$Conn->setConnectString("jdbc:mysql://localhost:3306/{$SYS["mysql"]["DBNAME"]}");

	$Conn->setUser($SYS["mysql"]["DBUSER"]);
	$Conn->setPassword($SYS["mysql"]["DBPASS"]);

	if ($Conn->getConnection()) {
	
	
		$parameters=new Java("java.util.HashMap");
		
		foreach ($P as $k=>$v) {
// 		echo $v;			
			if ($v["paratype"] == "Fecha") {
				$ts1=text_to_int($v["defaulty"]);
				$JAVA_PAR=new Java("java.util.Date",date("Y",$ts1)-1900,date("m",$ts1)-1,date("d",$ts1));
				$parameters->put($v["paraname"],$JAVA_PAR);

			} else if ($v["paratype"] == "Entero") {
				$JAVA_PAR=new Java("java.lang.Integer",$v["defaulty"]);
				$parameters->put($v["paraname"],$JAVA_PAR);
			} else if ($v["paratype"]=="Cadena") {
				$JAVA_PAR=new Java("java.lang.String",$v["defaulty"]);
				$parameters->put($v["paraname"],$JAVA_PAR);
			} else if ($v["paratype"]=="Decimal") {
				$JAVA_PAR=new Java("java.lang.Double",$v["defaulty"]);
				$parameters->put($v["paraname"],$JAVA_PAR);
			}
	 		else
				$parameters->put($v["paraname"],$v["defaulty"]);
		}

		$parameters->put("REPORT_DIR",$URLBASE."Pool/");
		//print_r($parameters);
		$print=$jfm->fillReport($report,$parameters,$Conn->getConnection());

		$filem=time();
		$finalname=session_save_path()."/coreg2_cache/{$SYS["ASCACHEDIR"]}/$filem.pdf";
		debug("DEBUG: $finalname $print informe:".$dir.$informe.".jrxml","yellow");
		$jem=new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
		$jem->exportReportToPdfFile($print, $finalname);

		//echo $jem->exportReportToPdf($print);
		ob_end_clean();
		if (file_exists($finalname)) {
			header("Content-Type: application/pdf");
			header("Content-Disposition: attachment; filename=\"$informe.pdf\"");
			readfile($finalname);
			//unlink($finalname);
		}

	} else
		echo "ERRORS";

// } catch (JavaException $ex) {
// 	/*$trace = new Java("java.io.ByteArrayOutputStream");
// 	$ex->printStackTrace(new Java("java.io.PrintStream", $trace));
// 	print "java stack trace: $trace\n";*/
// 	ob_end_clean();
// 	echo "ERROR<br>";
// 	echo "Cause: ".$ex->getCause()."<br>";
// 	echo "Message: ".$ex->getMessage()."<br>";
// }

?>