<!--Regeneracion de clases-->
<h2 align="center">Regeneracion de clases</h2>
<?php
$color=0x404661;
$dir=$SYS["DOCROOT"].$SYS["DATADEFPATH"];
echo "<br>Analizando Directorio $dir<br><table cellspacing=\"1\" cellpadding=\"2\" style=\"background-color:#00B200\">";
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {

			if ((filetype($dir . $file)!="dir")&&(strpos($file,".def")!==False)) {
					$buffer=file("http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."?command=make_class&class=".str_replace(".def","",$file));
					//echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."?command=make_class&class=".str_replace(".def","",$file);
	               			if ($buffer==FALSE)
						echo "<tr style=\"background -color: #FFFFFF;\"><td style=\"background -color: #FFFFFF;\">Error llamando a la pagina de test</td></tr>";
					else {
						$cnt=implode("\n",$buffer);
					
						if (strstr($cnt,"<!--STATUS:OK-->"))
							echo '<tr style=\"background -color: #FFFFFF;\"><td style=\"background -color: #FFFFFF;\">'.$file.'</td><td style=\"background -color: #FFFFFF;\">&nbsp;&nbsp;&nbsp; <b style="font-size:11px;color:green">OK</b><br></td></tr>'."\n";
						else
							echo '<tr style=\"background -color: #FFFFFF;\"><td style=\"background -color: #FFFFFF;\">'.$file.'</td><td style=\"background -color: #FFFFFF;\">&nbsp;&nbsp;&nbsp; <b style="font-size:11px;color:red">KO</b><br></td></tr>'."\n";;
						
					}

			}
	$color+=5;
	}
        closedir($dh);
    }
}

$dir=$SYS["BASE"]."/".$SYS["PROJECT"]."/local/Class/";
echo "</table><br><br>Analizando Directorio $dir<br><table cellspacing=\"1\" cellpadding=\"2\" style=\"background-color:#00B200\">";
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {

			if ((filetype($dir . $file)!="dir")&&(strpos($file,".def")!==False)) {
					$buffer=file("http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."?command=make_class&class=".str_replace(".def","",$file));
	               			if ($buffer==FALSE)
						echo "<tr style=\"background -color: #FFFFFF;\"><td style=\"background -color: #FFFFFF;\">Error llamando a la pagina de test</td></tr>";
					else {
						$cnt=implode("\n",$buffer);
					//print_r($buffer);
						if (strstr($cnt,"<!--STATUS:OK-->"))
							echo '<tr style=\"background -color: #FFFFFF;\"><td style=\"background -color: #FFFFFF;\">'.$file.'</td><td style=\"background -color: #FFFFFF;\">&nbsp;&nbsp;&nbsp; <b style="font-size:11px;color:green">OK</b><br></td></tr>'."\n";
						else
							echo '<tr style=\"background -color: #FFFFFF;\"><td style=\"background -color: #FFFFFF;\">'.$file.'</td><td style=\"background -color: #FFFFFF;\">&nbsp;&nbsp;&nbsp; <b style="font-size:11px;color:red">KO</b><br></td></tr>'."\n";
					}

			}
	$color+=5;
	}
        closedir($dh);
    }
}
?>
</table>