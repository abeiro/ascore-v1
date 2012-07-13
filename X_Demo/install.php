<?php
echo '<?xml version="1.0" encoding="iso-8859-15"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">';
?>

<head>
  <title>ASCore Install</title>
  <meta name="GENERATOR" content="Quanta Plus" />
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
  <style type="text/css">
  /* <![CDATA[ */
* {
	font-family:sans-serif;
	font-size:11px;
}
h2 {
	font-size:16px;
	width:512px;
	background-color:#BCD2EE;
}

div.tbox {
	
	background-color:#EEDFCC;
	border:1px solid gray;
	padding:5px;
	min-height:480px;
}

div.semitransbox {
	opacity:0.5;
	-moz-opacity:0.5;
	-khtml-opacity:0.5;		
	filter:alpha(Opacity=50);
	background-color:#EEDFCC;
	border:1px solid gray;
	padding:5px;
	min-height:480px;
}		

div.cell {
	width:150px;
	float:left;
	display:inline-block;
}
div.cell2 {
	width:300px;
	float:left;
	display:inline-block;
}

input [type=text] {

 	background-color:#E0EEE0
}

  /* ]]> */
  </style>
  <script language="javascript" type="text/javascript">
  /* <![CDATA[ */
var i=5;
var sw=0;
	       
function setOpacity(value,element) {
	       testObj=document.getElementById(element);
	       testObj.style.opacity = value/10;
	       testObj.style.KhtmlOpacity = value/10;
	       testObj.style.filter = 'alpha(opacity=' + value*10 + ')';
	       }
function opaShow(element) {
	       
		
	       if ((!sw)||(sw==0)) {
	       if (i<10) {
			 setOpacity(i,element);
			 i++;
			 setTimeout("opaShow('"+element+"')", 1);
			 }
			 else if (i==10)
			 sw=1;
			 }
			 else if (sw==1){
			 if (i>=5) {
		       setOpacity(i,element);
		       i--;
		       setTimeout("opaShow('"+element+"')", 1);
		       }
		       else if (i==4)
		       sw=0;
		       
	}
}  

  /* ]]> */
  </script>
</head>
<body>
	
<div align="center"><h2 align="center">ASCore Installation System</h2></div>
<div align="center">
<div align="center" style="width:512px">

<?php

if (empty($_POST["step"])) {

?>

	<!--PASO PREVIO -->
	<div align="justify" id="dtarget" class="tbox">
        <h3 align="center">Paso 1/3</h3>
	<p align="justify">Bienvenido al sistema de instalación automatizado de ASCore.</p>
	<p align="justify">Recuerde que una vez instalado el sistema deberá de borrar el fichero <strong>install.php</strong> de su servidor.</p>
	<p align="justify">Por favor, confirme que los siguientes valores son correctos...</p>
	<form method="post">
	<?php
	
	$URI=dirname($_SERVER["REQUEST_URI"]);
	$FURI=dirname($_SERVER["SCRIPT_FILENAME"]);
	echo "<div class=\"cell\">URL a la aplicacion:</div> <input type=\"text\" name=\"WEBROOT\" value=\"$URI\" size=\"45\"><br clear=\"all\" />";
	echo "<div class=\"cell\">Directorio en servidor:</div> <input type=\"text\"  name=\"FSROOT\" value=\"$FURI\" size=\"45\"><br clear=\"all\" />";	
	
	?>
         <br clear="all" />
	<input type="hidden" name="step" value="1" />
	<div align="right"><input type="submit" name="Confirmar" value="Confirmar" /></div>
	</form>
	</div>
	<!--PASO PREVIO -->

<?php

} else if (($_POST["step"])==1) {

	$CONF["ROOT"]=$_POST["FSROOT"];
	$CONF["URL"]=$_POST["WEBROOT"];
	
	$HTACCESS_SAMPLE=file_get_contents(".htaccess_sample");
	$HTACCESS_SAMPLE=preg_replace("/<([^\{]{1,100}?)>/e",'$CONF[$1]',$HTACCESS_SAMPLE);
	fwrite(fopen(".htaccess","w"),   $HTACCESS_SAMPLE);

?>

        <!--PASO UNO -->
	<div align="justify" id="dtarget" class="tbox">

	<h3 align="center">Paso 2/3</h3>
	<p align="justify">Se ha generado un fichero .htaccess con la configuración especificada anteriormente</p>
	<p align="justify">Ahora necesito crear la base de datos inicial, para ello necesitamos la contraseña de administrador de MySQL. El usuario administrador usualmente es "root" en sistemas Linux</p>
	<p align="justify">Por favor, rellene los siguientes valores...</p>
	<form method="post">

	<div class="cell2">Servidor MySQL</div> <input type="text" name="DBAHOST" value="localhost" size="25"><br clear="all" />

	<div class="cell2">Usuario administrador MySQL:</div> <input type="text" name="DBAUSER" value="root" size="15"><br clear="all" />
	
	<div class="cell2">Contraseña administrador MySQL:</div> <input type="password" name="DBAPASS" value="" size="15"><br clear="all" />

	<p align="justify">Note que puede crear el usuario y la base de datos "a mano". Si escoge esta opción, marque la siguiente casilla y sólo especifique la base de datos y el usuario de la misma para este proyecto</p>

	<div class="cell2">Sí, ya he creado la base de datos</div> <input type="checkbox" name="ALREADYCREATED" size="15"><br clear="all" />	

	<div class="cell2">Nombre de la base de datos de este proyecto:</div> <input type="text" name="DBDBNAME" value="ascore" size="15"><br clear="all" />
	<div class="cell2">Nombre del usuario BBDD para este proyecto:</div> <input type="text" name="DBDBUSER" value="ascore" size="15"><br clear="all" />
	<div class="cell2">Contraseña del usuario BBDD para este proyecto (solo necesario si el usuario ya existe):</div> <input type="password" name="DBDBPASS" value="" size="15"><br clear="all" />
	
	<br clear="all" />
	<input type="hidden" name="step" value="2" />
	<div align="right"><input type="submit" name="Confirmar" value="Confirmar" /><7div>
	</form>
	</div>
	<!--PASO UNO -->

<?php

} else if (($_POST["step"])==2) {

	//print_r($_POST);
	//resource mysql_connect ( [string server [, string username [, string password [, bool new_link [, int client_flags]]]]] )
         //Array ( [DBAHOST] => localhost [DBAUSER] => root [DBAPASS] => agupicam [DBDBNAME] => worldspace [DBDBUSER] => worldspace [step] => 2 [Confirmar] => Confirmar )
        //grant all on accounts.* to jsmith@localhost identified by 'Secret15';

?>
	<!--PASO DOS -->
	<div align="justify" id="dtarget" class="tbox">

	<h3 align="center">Paso 3/3</h3>
<?php
	if (empty($_POST["ALREADYCREATED"])) {
			error_reporting(E_ERROR);
			$DATA=$_POST;
			$DATA["SECRETKEY"]=md5(time());
	
			echo "* Chequeando conexion administracion..";
			if (mysql_connect($_POST["DBAHOST"],$_POST["DBAUSER"],$_POST["DBAPASS"]))
				echo " <strong>OK</strong><br />";
			else
				die(" INCORRECTO!!<br /></div>".mysql_error()."</div></div></body></html>");
			
			echo "* Creando base de datos....";
			mysql_query("DROP DATABASE {$_POST["DBDBNAME"]}");		
			if (mysql_query("CREATE DATABASE {$_POST["DBDBNAME"]}"))
				echo " <strong>OK</strong><br />";
			else
				die(" INCORRECTO!!<br /></div>".mysql_error()."</div></div></body></html>");
	
			echo "* Creando usuario....(contraseña generada)";
			$DATA["DBDBPASS"]=crypt(time(),'rl');
			if (mysql_query("GRANT ALL ON  {$_POST["DBDBNAME"]}.* TO {$_POST["DBDBUSER"]}@{$_POST["DBAHOST"]} IDENTIFIED BY '{$DATA["DBDBPASS"]}'"))
				echo " <strong><strong>OK</strong></strong><br />";
			else
				die(" INCORRECTO!!<br /></div>".mysql_error()."</div></div></body></html>");
	}  else {
		error_reporting(E_ERROR);
		$DATA=$_POST;
		$DATA["SECRETKEY"]=md5(time());
		$DATA["DBDBPASS"]=$_POST["DBDBPASS"];

	}

	echo "* Chequeando el nuevo usuario...";
	mysql_close();
	if (mysql_connect($_POST["DBAHOST"],$_POST["DBDBUSER"],$DATA["DBDBPASS"]))
		echo " <strong>OK</strong><br />";
	else
		die(" INCORRECTO!!<br /></div>".mysql_error()."</div></div></body></html>");
	
	echo "* Chequeando acceso a la base de datos...";
	if (mysql_select_db($_POST["DBDBNAME"]))
		echo " <strong>OK</strong><br />";
	else
		die(" INCORRECTO!!<br /></div>".mysql_error()."</div></div></body></html>");

	echo "* Creando fichero de configuración..";
	$CONF_SAMPLE=file_get_contents("Framework/conf_sample.php");
	$CONF_SAMPLE=preg_replace("/<([^\{]{1,100}?)>/e",'$DATA[$1]',$CONF_SAMPLE);
	fwrite(fopen("Framework/conf.php","w"),   $CONF_SAMPLE);


?>
	<p>Por favor, revise el fichero de configuración <strong>Framework/conf.php</strong></p>

	<p>Ahora necesitamos hacer una importación inicial de la base de datos. Puede que quiera saltarse este paso si solo está reconfigurando una instalación existente o simplemente lo va a hacer por su cuenta</strong></p>

	<form method="post">
	
	<div class="cell2">Sí, deseo hacer la imporación inicial</div><input type="checkbox" name="IMPORTPLEASE" size="15"><br clear="all" />	
        <br clear="all" />
	<input type="hidden" name="_DBDATA" value="<?php echo urlencode(serialize($DATA))?>" />
	<input type="hidden" name="step" value="3" />
	<div align="right"><input type="submit" name="Confirmar" value="Confirmar" /></div>
	</form>

	</div>
	<!--PASO DOS -->

<?php
} else if (($_POST["step"])==3) {

	$DATA=unserialize(urldecode($_POST["_DBDATA"]));
?>
	<!--PASO TRES -->
	<div align="justify" id="dtarget" class="tbox">
	<h3 align="center">Final</h3>
	<!--PASO TRES -->
<?php
	if (!empty($_POST["IMPORTPLEASE"])) {
		echo "* Chequeando conexion...";
		if (mysql_connect($DATA["DBAHOST"],$DATA["DBDBUSER"],$DATA["DBDBPASS"]))
			echo " <strong>OK</strong><br />";
		else
			die(" INCORRECTO!!<br /></div>".mysql_error()."</div></div></body></html>");
		echo "* Chequeando acceso a la base de datos...";
		
		if (mysql_select_db($DATA["DBDBNAME"]))
			echo " <strong>OK</strong><br />";
		else
			die(" INCORRECTO!!<br /></div>".mysql_error()."</div></div></body></html>");

		$_FILE="Data/ascore-initial.sql";
        	ini_set("max_execution_time","600");
		$errors=0;
		$success=0;
		$total=filesize($_FILE);
		$fd = fopen ($_FILE, "r");
  		while (!feof($fd)) {
      			$buffer.= fgets($fd, 8192);
            		$sbuffer=trim($buffer);
      			if (substr($sbuffer,strlen($sbuffer)-1,1)==";") {
		      	 /**********  UTF-8 PATCH */
	    	   		$bufferd=utf8_decode($buffer);
	    			$buffer=$bufferd;
			 /**********  UTF-8 PATCH */
				if (mysql_query($buffer))
					$success++;
				else {
					$last_error=mysql_error();
					$errors++;
				}				

		
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
  				
		}
		$oldp=$p;
  		fclose ($fd);
		echo "$success instrucciones ejecutadas :: $errors errores ($last_error)<br />".PHP_VERSION;

	}
	?>
        <div align="center"><h3>Su proyecto esta listo para ser usado!!!</h3></div>
	
	<?php



}

?>
</div>
</div>				
			
			
</body>		
</html>

