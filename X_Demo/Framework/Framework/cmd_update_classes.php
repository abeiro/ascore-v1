<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Resincronizando Base de Datos</title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
</head>
<body>
<div align="center"><IMG id="search" src="local/Image/xmag.gif" width="128" height="128" border="0"></div>
<?php
flush();
$color=0x404661;
$dir=$SYS["DOCROOT"].$SYS["DATADEFPATH"];
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {

			if ((filetype($dir . $file)!="dir")&&(strpos($file,".def")!==False)) {
					$buffer=file($SYS["ROOT"]."index.php?mod=framework&op=dev&command=make_class&class=".str_replace(".def","",$file));
	               			if ($buffer==FALSE)
						echo "Error llamando a la pagina de test";
					else {
						$cnt=implode("\n",$buffer);
					//print_r($buffer);
						
						if (strstr($cnt,"<!--STATUS:OK-->")) {
							echo "";
							
						}
						else
							echo "$file KO";
						
					}

			}
	$color+=5;
	}
        closedir($dh);
    }
}

$dir=$SYS["BASE"]."/local/Class/";

if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {

			if ((filetype($dir . $file)!="dir")&&(strpos($file,".def")!==False)) {
					$buffer=file($SYS["ROOT"]."index.php?mod=framework&op=dev&command=make_class&class=".str_replace(".def","",$file));
	               			if ($buffer==FALSE)
						echo "Error llamando a la pagina de test";
					else {
						$cnt=implode("\n",$buffer);
					//print_r($buffer);
						if (strstr($cnt,"<!--STATUS:OK-->"))
							echo "";
						else
							echo "$file KO";
					}

			}
	$color+=5;
	}
        closedir($dh);
    }
}
?>
<script>
document.getElementById("search").src="local/Image/xmagdone.gif";
</script>
</body>
</html>
