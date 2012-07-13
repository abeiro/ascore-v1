<?php
require_once("System.php");
plantHTML(array(),"f_menu");


$all_classes=array();
$modules=array("Bilo","Cursos","Personas","System");
foreach ($modules as $k=>$module) { 
	$dir=$SYS["BASE"]."/$module/local/Class/";
	debug("=>Reading ".$SYS["BASE"]."/$module/local/Class/","yellow");
	if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
				if ((filetype($dir . $file)!="dir")&&(strpos($file,".def")!==False)) {
					$all_classes[]=str_replace(".def","",$file);
				}
			}
		}
	}
}

$dir=$SYS["DOCROOT"].$SYS["DATADEFPATH"];
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
			if ((filetype($dir . $file)!="dir")&&(strpos($file,".def")!==False)) {
				$all_classes[]=str_replace(".def","",$file);
			}
		}
	}
}
 
foreach ($SYS["APPS"] as $app) {
	$dir=$SYS["BASE"]."/Apps/".$app."/local/Class/";
	//echo $dir;
	if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
		if (is_file($dir . $file))
				if ((filetype($dir . $file)!="dir")&&(strpos($file,".def")!==False)) {
					$all_classes[]=str_replace(".def","",$file);
				}
			}
		}
	}
}
?>
<br><br><br>
<table width="70%" cellspacing="0" border="0" cellpadding="5" align="center">
  <tbody>
    <tr>
      <td align="center"> 

		<form action="<?php echo $SYS["ROOT"] ?>/Backend/System/do_import_csv.php" method="POST" enctype="multipart/form-data" >
		<input type="file" name="fichero_csv"><br><br>
		<select name="table"><br>
		<?php
        	foreach ($all_classes as $k=>$v)
				echo "<option value=\"$v\">$v</option>";

		?>
		</select>
		<br><br>
		<input type="submit"> 
		</form>

	 </td>
    </tr>
  </tbody>
</table>

<?php
HTML("footer");
?>