<?php
$info=new core();
?>
<h1 align="center">Core G2 </h1>
<div align="center">Version : <?php echo $info->version();?><br>
Fecha: <?php echo strftime("%d/%m/%Y",$info->release());?></div>

<div align="center">
<?php echo '
[ <a href="'.$_SERVER["SCRIPT_URI"].'?command=make_all&void_framming=yes">ReGenerar todas</a> ]  || 
[ <a href="'.$_SERVER["SCRIPT_URI"].'?test_status=True">Chequear</a> ] || 
[ <a href="'.$_SERVER["SCRIPT_URI"].'?command=rootdoc&nodebug=True">Doc: root</a> ] ||
[ <a href="'.$_SERVER["SCRIPT_URI"].'?command=delcache&nodebug=True">Borrar cache</a> ] ||
[ <a href="'.$_SERVER["SCRIPT_URI"].'?command=phpinfo&nodebug=True">PHPinfo</a> ]
[ <a href="'.$_SERVER["SCRIPT_URI"].'?command=selfphpinfo&nodebug=True">PHPClient PHPinfo</a> ]
[ <a href="'.$_SERVER["SCRIPT_URI"].'?command=querybuilder">QueryBuilder</a> ]
[ <a href="'.$_SERVER["SCRIPT_URI"].'?command=asistente">Nueva Definicion</a> ]

';?>
</div>


<pre style="font : 9px Arial, Helvetica, Sans, 'Sans Serif';">
<?php

$dir=$SYS["DOCROOT"].$SYS["DATADEFPATH"];
echo "Analizando Directorio $dir\n\n";
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {

			if ((filetype($dir . $file)!="dir")&&(strpos($file,".def")!==False)) {
				echo "[ <a href=\"".$_SERVER["SCRIPT_URI"]."?command=make_class&class=".str_replace(".def","",$file)."\">Generar</a>] ";
				echo "[ <a href=\"".$_SERVER["SCRIPT_URI"]."?command=test&module=".$SYS["PROJECT"]."&class=".str_replace(".def","",$file)."\">Propiedades</a>] ";
				
				
				
				
				echo "[  <a href=\"".$_SERVER["SCRIPT_URI"]."?command=edit_class&class=".str_replace(".def","",$file)."\">Editar</a> ] ";
				echo "[  <a target=\"_blank\" href=\"".$_SERVER["SCRIPT_URI"]."?command=edit_template&void_framming=yes&class=".str_replace(".def","",$file)."&nodebug=1&action=yes\">Pl.Edicion</a> ] ";

				echo "[  <a target=\"_blank\" href=\"".$_SERVER["SCRIPT_URI"]."?command=edit_template2&void_framming=yes&class=".str_replace(".def","",$file)."&nodebug=1&action=yes\">Pl.Edicion2</a> ] ";


				echo "[  <a target=\"_blank\" href=\"".$_SERVER["SCRIPT_URI"]."?command=view_template&void_framming=yes&class=".str_replace(".def","",$file)."&nodebug=1&action=yes\">Pl.Visualizacion</a> ] ";
				echo "[  <a target=\"_blank\" href=\"".$_SERVER["SCRIPT_URI"]."?command=list_template&void_framming=yes&class=".str_replace(".def","",$file)."&nodebug=1&action=yes\">Pl.Listado</a> ] ";
				echo "[  <a  href=\"".$_SERVER["SCRIPT_URI"]."?command=change_type&class=".str_replace(".def","",$file)."\">Cambiar tipo</a> ] ";

				echo "[  <a href=\"".$_SERVER["SCRIPT_URI"]."?command=delete_class&class=".str_replace(".def","",$file)."\">Borrar Clase</a> ] ";


	            
                if ($test_status) {
					//echo $SYS["ROOT"]."index.php?mod=framework&op=dev&command=test&module=none&class=".str_replace(".def","",$file);
					$buffer=file($SYS["ROOT"]."dev.php?command=test&class=".str_replace(".def","",$file));
	               	if ($buffer==FALSE)
						echo "Error llamando a la pagina de test";
					$cnt=implode("\n",$buffer);
					//print_r($buffer);
					if (strstr($cnt,"<!--STATUS:OK-->"))
						echo '<b style="color:green">OK  ';
					else
						echo '<b style="color:red">KO  ';
				}

				print " $file\n" ;

				
				
			}
				
			else
				echo "";
        }
        closedir($dh);
    }
}

$dir=$SYS["BASE"]."/Apps/".$SYS["PROJECT"]."/local/Class/";
echo "<br>Analizando Directorio $dir\n\n";
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {

			if ((filetype($dir . $file)!="dir")&&(strpos($file,".def")!==False)) {
				echo "[  <a href=\"".$_SERVER["SCRIPT_URI"]."?command=make_class&class=".str_replace(".def","",$file)."\">Generar</a> ] ";
				echo "[  <a href=\"".$_SERVER["SCRIPT_URI"]."?command=test&module=".$SYS["PROJECT"]."&class=".str_replace(".def","",$file)."\">Propiedades</a> ] ";
				echo "[  <a href=\"".$_SERVER["SCRIPT_URI"]."?command=edit_class&class=".str_replace(".def","",$file)."\">Editar</a> ] ";
				echo "[  <a target=\"_blank\" href=\"".$_SERVER["SCRIPT_URI"]."?command=edit_template&void_framming=yes&class=".str_replace(".def","",$file)."&nodebug=1&action=yes\">Pl.Edicion</a> ] ";
				
				echo "[  <a target=\"_blank\" href=\"".$_SERVER["SCRIPT_URI"]."?command=edit_template2&void_framming=yes&class=".str_replace(".def","",$file)."&nodebug=1&action=yes\">Pl.Edicion2</a> ] ";		

				echo "[  <a target=\"_blank\" href=\"".$_SERVER["SCRIPT_URI"]."?command=view_template&void_framming=yes&class=".str_replace(".def","",$file)."&nodebug=1&action=yes\">Pl.Visualizacion</a> ] ";
				echo "[  <a target=\"_blank\" href=\"".$_SERVER["SCRIPT_URI"]."?command=list_template&void_framming=yes&class=".str_replace(".def","",$file)."&nodebug=1&action=yes\">Pl.Listado</a> ] ";
				echo "[  <a  href=\"".$_SERVER["SCRIPT_URI"]."?command=change_type&class=".str_replace(".def","",$file)."\">Cambiar tipo</a> ] ";
			
				echo "[  <a href=\"".$_SERVER["SCRIPT_URI"]."?command=delete_class&class=".str_replace(".def","",$file)."\">Borrar Clase</a> ] ";	


	            
                if ($test_status) {
					//echo $SYS["ROOT"]."index.php?mod=framework&op=dev&command=test&module=none&class=".str_replace(".def","",$file);
					$buffer=file($SYS["ROOT"]."".$_SERVER["SCRIPT_URI"]."?command=test&class=".str_replace(".def","",$file));
	               	if ($buffer==FALSE)
						echo "Error llamando a la pagina de test";
					$cnt=implode("\n",$buffer);
					//print_r($buffer);
					if (strstr($cnt,"<!--STATUS:OK-->"))
						echo '<b style="color:green">OK  ';
					else
						echo '<b style="color:red">KO  ';
				}

				print " $file\n" ;

				
				
			}
				
			else
				echo "";
        }
        closedir($dh);
    }
}
?>
</pre>
<br>
<form action="?command=asistente" method="POST" enctype="multipart/form-data">
<div align="center">Nueva clase: <input type="text" name="class"><input type="submit">  </div>
</form>
<hr>
