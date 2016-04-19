<?php
echo '<?xml version="1.0" encoding="iso-8859-15"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">';
?>

<head>
  <title>ASCore Install</title>
  <meta name="GENERATOR" content="Quanta Plus" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700' rel='stylesheet' type='text/css'>
  <style type="text/css">
  /* <![CDATA[ */
* {
	font-family:'Open Sans';
	font-size: 13px;
}
h2 {
  font-size: 32px;
  /* width: 512px; */
  /* background-color: #BCD2EE; */
  /* text-shadow: 2px 2px green; */
  color:#008FC5;
  text-shadow: 0 1px 0 gray;
}

div.tbox {
  /* background-color: #EEDFCC; */
  border: 1px solid lightgray;
  padding: 15px;
  min-height: 480px;
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
	
<div align="center"><h2 align="center">asCore install</h2></div>
<div align="center">
<div align="center" style="width:712px">

<?php

if (empty($_POST["step"])) {

?>

	<!--PASO PREVIO -->
	<div align="justify" id="dtarget" class="tbox">
        <h3 align="center">Step 1/3</h3>
	<p align="justify">Welcome!!</p>
	<p align="justify">Please, change perms or delete file "<strong>install.php</strong>" from your server once install is completed.</p>
	<p align="justify">confirm below values</p>
	<form method="post">
	<?php
	
	$URI=dirname($_SERVER["REQUEST_URI"]);
	$FURI=dirname($_SERVER["SCRIPT_FILENAME"]);
	echo "<div class=\"cell\">Site URL:</div> <input type=\"text\" name=\"WEBROOT\" value=\"$URI\" size=\"45\"><br clear=\"all\" />";
	echo "<div class=\"cell\">Site Server Path:</div> <input type=\"text\"  name=\"FSROOT\" value=\"$FURI\" size=\"45\"><br clear=\"all\" />";	
	
	?>
         <br clear="all" />
	<input type="hidden" name="step" value="1" />
	<div align="right"><input type="submit" name="Confirm" value="Confirm" /></div>
	</form>
	</div>
	<!--PASO PREVIO -->

<?php

} else if (($_POST["step"])==1) {

	$CONF["ROOT"]=$_POST["FSROOT"];
	$CONF["URL"]=$_POST["WEBROOT"];
	print_r($_POST);
	$HTACCESS_SAMPLE=file_get_contents(".htaccess_sample");
	$HTACCESS_SAMPLE2=preg_replace_callback("/<([^\{]{1,100}?)>/",function($tok){ return $GLOBALS["CONF"][$tok[1]]; },$HTACCESS_SAMPLE);
	$fileOut=fopen(".htaccess","w") or die("Couldn't write .htaccess file, check permissions and reload page");
	fwrite($fileOut,   $HTACCESS_SAMPLE2);

?>

        <!--PASO UNO -->
	<div align="justify" id="dtarget" class="tbox">

	<h3 align="center">Step 2/3</h3>
	<p align="justify">A.htaccess file has been generated with the configuration specified above</p>
	<p align="justify">Now we must create the initial database, so we need the MySQL administrator password . The user administrator is usually "root " on Linux systems</p>
	<p align="justify">Please, fill in below values...</p>
	<form method="post">

	<div class="cell2">MySQL Server</div> <input type="text" name="DBAHOST" value="localhost" size="25"><br clear="all" />

	<div class="cell2">MySQL admin user:</div> <input type="text" name="DBAUSER" value="root" size="15"><br clear="all" />
	
	<div class="cell2">MySQL admin pass:</div> <input type="password" name="DBAPASS" value="" size="15"><br clear="all" />

	<p align="justify">Note that you can create the user and the database manually. If you choose this option , check below checkbox and just specify the database name and user for this project</p>

	<div class="cell2">Yes, I have already created the database</div> <input type="checkbox" name="ALREADYCREATED" size="15"><br clear="all" />	

	<div class="cell2">Site DB name:</div> <input type="text" name="DBDBNAME" value="ascore" size="15"><br clear="all" />
	<div class="cell2">Site DB user name:</div> <input type="text" name="DBDBUSER" value="ascore" size="15"><br clear="all" />
	<div class="cell2">Site DB user passowrd:</div> <input type="password" name="DBDBPASS" value="" size="15"><br clear="all" />
	
	<br clear="all" />
	<input type="hidden" name="step" value="2" />
	<div align="right"><input type="submit" name="Confirmar" value="Confirm" /></div>
	</form>
	</div>
	<!--PASO UNO -->

<?php

} else if (($_POST["step"])==2) {

	//print_r($_POST);
	//resource 


	//connect ( [string server [, string username [, string password [, bool new_link [, int client_flags]]]]] )
         //Array ( [DBAHOST] => localhost [DBAUSER] => root [DBAPASS] => agupicam [DBDBNAME] => worldspace [DBDBUSER] => worldspace [step] => 2 [Confirmar] => Confirmar )
        //grant all on accounts.* to jsmith@localhost identified by 'Secret15';

?>
	<!--PASO DOS -->
	<div align="justify" id="dtarget" class="tbox">

	<h3 align="center">Step 3/3</h3>
<?php
	if (empty($_POST["ALREADYCREATED"])) {
			error_reporting(E_ERROR);
			$DATA=$_POST;
			$DATA["SECRETKEY"]=md5(time());
	
			echo "* Checking DB connection...";
			if ($DBLINK=mysqli_connect($_POST["DBAHOST"],$_POST["DBAUSER"],$_POST["DBAPASS"]))
				echo " <strong>OK</strong><br />";
			else
				die(" Failed!!<br /></div>".mysqli_error($DBLINK)."</div></div></body></html>");
			
			echo "* Creating DB....";
			mysqli_query($DBLINK,"DROP DATABASE {$_POST["DBDBNAME"]}");		
			if (mysqli_query($DBLINK,"CREATE DATABASE {$_POST["DBDBNAME"]}"))
				echo " <strong>OK</strong><br />";
			else
				die(" Failed!!<br /></div>".mysqli_error($DBLINK)."</div></div></body></html>");
	
			echo "* Creating user password...";
			$DATA["DBDBPASS"]=crypt(time(),'rl');
			if (mysqli_query($DBLINK,"GRANT ALL ON  {$_POST["DBDBNAME"]}.* TO {$_POST["DBDBUSER"]}@{$_POST["DBAHOST"]} IDENTIFIED BY '{$DATA["DBDBPASS"]}'"))
				echo " <strong><strong>OK</strong></strong><br />";
			else
				die(" Failed!!<br /></div>".mysqli_error($DBLINK)."</div></div></body></html>");
	}  else {
		error_reporting(E_ERROR);
		$DATA=$_POST;
		$DATA["SECRETKEY"]=md5(time());
		$DATA["DBDBPASS"]=$_POST["DBDBPASS"];

	}

	echo "* Checking new user...";
	mysqli_close();
	if ($DBLINK=mysqli_connect($_POST["DBAHOST"],$_POST["DBDBUSER"],$DATA["DBDBPASS"]))
		echo " <strong>OK</strong><br />";
	else
		die(" Failed!<br /></div>".mysqli_error($DBLINK)."</div></div></body></html>");
	
	echo "* Checking DB access...";
	if (mysqli_select_db($DBLINK,$_POST["DBDBNAME"]))
		echo " <strong>OK</strong><br />";
	else
		die(" Failed!<br /></div>".mysqli_error($DBLINK)."</div></div></body></html>");

	echo "* Creating configuration file...";
	$CONF_SAMPLE=file_get_contents(dirname(__FILE__)."/Framework/conf_sample.php");
	$CONF_SAMPLE2=preg_replace_callback("/<([^\{]{1,100}?)>/",function($tok){ return $GLOBALS["DATA"][$tok[1]]; },$CONF_SAMPLE);
	fwrite(fopen(dirname(__FILE__)."/Framework/conf.php","w"),   $CONF_SAMPLE2);


?>
	<p>Please, check configuration file <strong>Framework/conf.php</strong></p>

	<p>Now we must do an initial DB import. You can skip this step unchecking below checkbox.</strong></p>

	<form method="post">
	
	<div class="cell2">Do initial DB import</div><input type="checkbox" checked name="IMPORTPLEASE" size="15"><br clear="all" />	
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
		echo "* Checking connection...";
		if ($DBLINK=mysqli_connect($DATA["DBAHOST"],$DATA["DBDBUSER"],$DATA["DBDBPASS"]))
			echo " <strong>OK</strong><br />";
		else
			die(" Failed!<br /></div>".mysqli_error($DBLINK)."</div></div></body></html>");
		echo "* Checking DB access...";
		
		if (mysqli_select_db($DBLINK,$DATA["DBDBNAME"]))
			echo " <strong>OK</strong><br />";
		else
			die(" Failed!<br /></div>".mysqli_error($DBLINK)."</div></div></body></html>");

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
				if (mysqli_query($DBLINK,$buffer))
					$success++;
				else {
					$last_error=mysqli_error($DBLINK);
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
		echo "* $success executed queries :: $errors error(s) ($last_error)<br />* PHP version: ".PHP_VERSION;

	}
	?>
        <div align="center"><h3>Installation has finished</h3></div>
	
	<?php



}

?>
</div>
</div>				
			
			
</body>		
</html>

