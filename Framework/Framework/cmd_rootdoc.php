<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title></title>
  <meta name="GENERATOR" content="Quanta Plus">
</head>
<style>
body {
	font-family:monospace;
	font-size:9px;
	}
</style>
<body>
<?php
# COment?
set_time_limit  ( 1600);

function ls($dir) {
	global $SYS;
	echo $SYS["DOCROOT"]."/".$dir."<br>";
	$handle=opendir($SYS["DOCROOT"]."/".$dir); 
	while ($file = readdir($handle)) { 
		if ($file != "." && $file != "..") { 
			$res[]="$dir/$file"; 
		} 
	}
	closedir($handle);
	return $res;
}

function Cbr($cl,$token="") {

	if (!empty($token))
		$res[]=$token;
	
	$f=$cl;
	
	while (!empty($f)) {
		$res[]=$f;
		$f=get_parent_class($f);
	}
	//print_r($res);
	
	for ($i=sizeof($res);$i--;$i>=0)
		if ($i!=0)
			$s.='<b>'.$res[$i].'</b>'."::";
		else
			$s.='<b>'.$res[$i].'</b>';

	return $s;


}

function parse_code($file) {
	global $SYS,$NAMES,$METHODS;
	$root_file=file($SYS["DOCROOT"]."/$file");
	$source=implode("\n",$root_file);
	echo "Funciones: ".preg_match_all("/function (.*)\(.*\)/",$source,$data)."\n<br>";
	foreach ($data[1] as $n=>$token) {
		
		$linea=preg_grep("/function (.*)($token)\(.*\)/",$root_file);
		$n=key($linea);
		$m=$n;	
		while (strpos($root_file[$m],"{")===False) {
			$m++;
			if ($m>25000) {
             	echo "<b>Limite 25000!!!!!</b><br>";
             	break;
			}
		}
		//echo "<br>Mixing code...$token at $m\n<br>";
		//flush();
		$flag=True;
		$stack=0;
		$stack_ini=False;
		$code="";
		while ($flag) {
			$code.=$root_file[$m];
			if (strpos($root_file[$m],"{")!==False) {
				$stack++;
				$stack_ini=True;
			}
			if (strpos($root_file[$m],"}")!==False)
				$stack--;
			$m++;

			if (($stack_ini=True) && $stack==0)
				$flag=false;

			if ($m>sizeof($root_file))
				$flag=false;

			if ($m>25000) {
				$flag=false;
             	echo "<b>Limite 25000!!!!!</b><br>";
			}


		}
		for ($m=$n;$m--;$m>0) {
				//echo $m.".".$root_file[$m]."\n<br>";
				if (strstr($root_file[$m],"}")!==False)
					break;
				if (strstr($root_file[$m],"/*")!==False)
					break;
		}

		$desc="";

		if (class_exists($token)) {
			$desc.='<h2>'.Cbr($token,$token).'</h2>'."";
			$desc.="Constructor de clase <b>$token</b><br>";
			$desc.="Clase <b>$token</b> </i>: derivada de <b>".get_parent_class($token)."</b><br><br>";

		}
		else if (function_exists($token))
			$desc.="Funcion<br><br>";
		else {	
			/* Entonces deber�a ser un m�todo */
			$all=array_splice(get_declared_classes(),3);
			//print_r($all);
			foreach ($all as $k=>$cl) {
				if (in_array($token,get_class_methods($cl))) {
					$desc.='<h2>'.Cbr($cl,$token).'</h2>'."";
					$desc.="M�todo: pertenece a la clase <b>$cl</b><br>";
					$desc.="Clase <b>$cl</b> </i>: derivada de <b>".get_parent_class($cl)."</b><br>";


				}
			}
		}
        flush();

		$desc.="<br><br><div class=\"desc\">".implode("<br>",array_slice($root_file,$m,$n-$m))."</div>\n";
		$desc= str_replace("/*","",$desc);
		$desc= str_replace("*/","",$desc);
		$fakname=explode(".",basename($file));
		$out=fopen($SYS["DOCROOT"]."Doc/".$fakname[0].".$token.html","wt");
		$NAMES[$fakname[0]][]=$token;


		if ((ini_set("highlight.bg",      "#FFFFFF") &&
		ini_set("highlight.comment", "#FF8000") &&
		ini_set("highlight.default", "#0000BB") &&
		ini_set("highlight.html",    "#000000") &&
		ini_set("highlight.keyword", "#007700") &&
		ini_set("highlight.string",  "#DD0000"))==False)
			echo "Error";

		ob_start();

		highlight_string('<?php

	'.$code.'

	?>');
		$fcode="<br><div class=\"box\">".ob_get_contents()."</div>";
		ob_end_clean();
		$ret="";
		$data_lines="";
		$head='
		<html>
		<head>
		<title>Documentacion CoreG2 Funcion '.$token.'</title>
		</head>
		<style>
		body 		{font-family:verdana,sans,serif; font-size:10px;}
		.mini		{font-size:8px;}
		.box		{border:1px solid gray;background:#EEEEFF;}
		.desc		{font-size:12px;border:1px solid gray; padding:5px;}   
		</style>
		<body>
		<h1>CoreG2 version '.
		_COREVER
		.'</h1>
		<h2>'.$token.'</h2>
		<span class="mini">Fichero '.$SYS["DOCROOT"]."$file".'</span>
		<br><br>';

		$footer="
		<br><br>
		<div align=\"center\"><a href=\"$fakname[0]-index.html\">up</a></div>
		<br><br>
		</body>
		";
		fwrite($out,$head.$desc.$fcode.$footer);
		fclose($out);


	}
}

/* Clases principales */

//$docs=array("SubCore/core.php","SubCore/root.php","SubCore/mysql.inc.php");
$docs=ls("SubCore");
$NAMES=array(array());
$METHODS=array();
$filelist="";

foreach ($docs as $k=>$file) {
	echo "Parseando $file\n....";
	parse_code($file);
}
echo "<pre>";print_r($NAMES);echo "</pre>";

$NAMES=array_slice($NAMES,1);

foreach (array_keys($NAMES) as $k=>$v) {
	$out=fopen($SYS["DOCROOT"]."Doc/$v-index.html","wt");
	$mdata='
	<html>
		<head>
		<title>Documentacion CoreG2. Grupo '.$v.'</title>
		</head>
		<style>
		body 		{font-family:verdana,sans,serif; font-size:10px;}
		.mini		{font-size:8px;}
		.box		{border:1px solid gray;background:#EEEEFF;}
		.desc		{font-size:10px;border:1px solid gray; padding:5px;}   
		</style>
		<body>
		<h1>CoreG2 version '.
		_COREVER
		.'</h1>
		<h2>Grupo '.$v.'</h2>
		<br><br>';

	foreach ($NAMES[$v] as $mk=>$mv) 
		$mdata.="<a href=\"$v.$mv.html\">$mv</a><br>";
	fwrite($out,$mdata);
	fclose($out);
	$c_f_rel.="$v=$v-index.html\n";
}
		

foreach ($NAMES as $fls=>$met)
	$context=array_merge($context,array_values($NAMES[$fls]));

foreach ($context  as $k=>$v)
	foreach ($NAMES as $mk=>$mv)
		if (in_array($v,$mv))
			$filelist.="$v=$mk.$v.html\n";
$main_classes=implode(",",array_keys($NAMES));


/* Librer�as */

//$docs=array("Lib/lib_list.php","Lib/lib_planty.php");
$docs=ls("Lib");
$NAMES=array(array());
$METHODS=array();

foreach ($docs as $k=>$file) {
	echo "Parseando $file\n.....";
	parse_code($file);
}
echo "<pre>";print_r($NAMES);echo "</pre>";

$NAMES=array_slice($NAMES,1);

foreach (array_keys($NAMES) as $k=>$v) {
	$out=fopen($SYS["DOCROOT"]."Doc/$v-index.html","wt");
	$mdata='
	<html>
		<head>
		<title>Documentacion CoreG2. Grupo '.$v.'</title>
		</head>
		<style>
		body 		{font-family:verdana,sans,serif; font-size:10px;}
		.mini		{font-size:8px;}
		.box		{border:1px solid gray;background:#EEEEFF;}
		.desc		{font-size:10px;border:1px solid gray; padding:5px;}   
		</style>
		<body>
		<h1>CoreG2 version '.
		_COREVER
		.'</h1>
		<h2>Grupo '.$v.'</h2>
		<br><br>';
	foreach ($NAMES[$v] as $mk=>$mv) 
		$mdata.="<a href=\"$v.$mv.html\">$mv</a><br>";
	fwrite($out,$mdata);
	fclose($out);
	$l_f_rel.="$v=$v-index.html\n";
}
		

foreach ($NAMES as $fls=>$met)
	$context=array_merge($context,array_values($NAMES[$fls]));

foreach ($context  as $k=>$v)
	foreach ($NAMES as $mk=>$mv)
		if (in_array($v,$mv))
			$filelist.="$v=$mk.$v.html\n";

$main_lib=implode(",",array_keys($NAMES));


//print_r($context);
$template_quanta_doc="
[Tree]

Doc dir=Doc/

#top level elements
Top Element=Documentacion FrameWork ".$SYS["PROJECT"]."

Documentacion FrameWork ".$SYS["PROJECT"]."=Bienvenido,Acerca de,#Clases principales,#Librerias
Bienvenido=intro.html
Acerca de=about.html
Clases principales=$main_classes

$c_f_rel


Librerias=$main_lib

$l_f_rel

# keywords for context help
[Context]

ContextList=".implode(",",$context)."

$filelist
";
echo "<hr>";
echo "<pre>$template_quanta_doc</pre>";

$out=fopen($SYS["DOCROOT"]."Doc/".$SYS["PROJECT"].".docrc","wt");
fwrite($out,$template_quanta_doc);
fclose($out);

$out=fopen($SYS["DOCROOT"]."Doc/index.html","wt");
fwrite($out,'
	<html>
		<head>
		<title>Documentacion CoreG2. Grupo '.$v.'</title>
		</head>
		<style>
		body 		{font-family:verdana,sans,serif; font-size:10px;}
		.mini		{font-size:8px;}
		.box		{border:1px solid gray;background:#EEEEFF;}
		.desc		{font-size:10px;border:1px solid gray; padding:5px;}   
		</style>
		<body>
		<h1>CoreG2 version '.
		_COREVER
		.'</h1>');
		
$output="";

$mc=explode("\n",$c_f_rel);
foreach ($mc as $k=>$v ) {
	$mv=explode("=",$v);
	$output.='<a href="'.$mv[1].'">'.$mv[0].'<br>';
}
echo "<pre>$output</pre>";
fwrite($out,$output);


$output="";
$mc=explode("\n",$l_f_rel);
foreach ($mc as $k=>$v ) {
	$mv=explode("=",$v);
	$output.='<a href="'.$mv[1].'">'.$mv[0].'<br>';
}
echo "<pre>$output</pre>";
fwrite($out,$output);
fwrite($out,'</body></html>');


fclose($out);

?>
  
</body>
</html>