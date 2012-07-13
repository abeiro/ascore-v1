<?php

require_once("JasperReports.php");
require_once("Bilo/API_exports.php");
require_once("common_data.php");

$_SESSION["origin"]=$name;

$XML_file="interface_".$name.".xml";
$path="local/XML/".$XML_file;
$file=file($path,FILE_USE_INCLUDE_PATH);
$xml=implode("\n",$file);

$xmlObj=new XmlToArray($xml);
$arrayData=$xmlObj->createArray();

$entity=$arrayData["interface"]["entity"];
$arrayRelationships=$arrayData["interface"]["relationships"];

// Construimos un array con las clases que tienen informe asociado
$jasperreport_q=newObject("jasperreport");
$jasperreport=$jasperreport_q->selectA();
$clases_asociadas_informes=array();
foreach($jasperreport as $k=>$v)
	$clases_asociadas_informes[]=$v["class"];

// Icono para indicar que la clase asociada al botón tiene informe asociado
$report_mini_icon_url=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/report_mini.png";

foreach($arrayRelationships as $k=>$v) {
	if($v["name"] == $entity)
		$action="actionButtonEntity()";
	else
		$action="actionButtonRelationship('".$v["name"]."')";

	$SYS["buttons"].="<input type=\"button\" name=\"r_buttons\" id=\"r_".$v["name"]."\" value=\"".$v["label"]."\" onclick=\"".$action."\"/>";

	if(in_array($v["name"],$clases_asociadas_informes))
		$SYS["buttons"].="<img class=\"mini_icon\" name=\"informe_asociado\" id=\"informe_asociado\" src=\"".$report_mini_icon_url."\" alt=\"Informe asociado\" title=\"Informe asociado\"><br>\n";
	else
		$SYS["buttons"].="<br>\n";

	$SYS["hiddens"].="<input type=\"hidden\" name=\"h_".$v["name"]."\" id=\"h_".$v["name"]."\" size=\"2\"/>\n";
	$SYS["hiddens"].="<input type=\"hidden\" name=\"h_backup_".$v["name"]."\" id=\"h_backup_".$v["name"]."\" size=\"2\"/>\n";
	$SYS["hiddens"].="<input type=\"hidden\" name=\"h_offset_".$v["name"]."\" id=\"h_offset_".$v["name"]."\" size=\"2\"/>\n";
	$SYS["hiddens"].="<input type=\"hidden\" name=\"h_offset_backup_".$v["name"]."\" id=\"h_offset_backup_".$v["name"]."\" size=\"2\"/>\n";
	if($v["name"] != $entity)
		$SYS["hiddens"].="<input type=\"hidden\" name=\"h_depends_".$v["name"]."\" id=\"h_depends_".$v["name"]."\" value=\"".$v["depends"]."\" size=\"2\"/>\n";
}

$SYS["hiddens"].="<input type=\"hidden\" name=\"h_entity\" id=\"h_entity\" value=\"".$entity."\" size=\"2\"/>\n";
$SYS["hiddens"].="<input type=\"hidden\" name=\"h_checked\" id=\"h_checked\" size=\"2\"/>\n";

$SYS["hiddens"].="<input type=\"hidden\" name=\"h_search\" id=\"h_search\" value=\"0\" size=\"2\"/>\n";
$SYS["hiddens"].="<input type=\"hidden\" name=\"h_search_entity\" id=\"h_search_entity\" size=\"2\"/>\n";

$SYS["new_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/new.png";
$SYS["list_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/list.png";
$SYS["edit_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/edit.png";
$SYS["save_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/save.png";
$SYS["up_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/up.png";
$SYS["down_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/down.png";
$SYS["up_tab_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/up_tab.png";
$SYS["down_tab_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/down_tab.png";
$SYS["print_preview_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/print_preview.png";
$SYS["report_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/report.png";
$SYS["view_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/save_view.png";
$SYS["show_all_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/show_all.png";
$SYS["search_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/search_disabled.png";
$SYS["export_csv_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/export_csv.png";

$SYS["mainframe_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/public_w_operation.php?CL=".$entity;


// Gestión acciones permitidas --------------------------------------------

if(isset($_SESSION["PERMISOS"]["acciones"])) {
	$acciones_permitidas=explode(",",$_SESSION["PERMISOS"]["acciones"]);
	foreach($acciones as $a) {
		if(in_array($a,$acciones_permitidas))
			$SYS["permiso_$a"]=1;
		else
			$SYS["permiso_$a"]=0;
	}
}

//-------------------------------------------------------------------------


// Si nos encontramos en 'Informes Jasper -> Informes disponibles' o en 'Informes Jasper -> Gestión' ocultamos el botón desactivar porque no se usará
if($_SESSION["origin"] == "report" || $_SESSION["origin"] == "jasperreport")
	$SYS["deactivate_type"]="hidden";
else
	$SYS["deactivate_type"]="image";

// Los botones para borrar y desactivar estarán visibles para los administradores por temas de desarrollo, mientras que para el resto de grupos únicamente el botón para desactivar
if(BILO_checkGroup("Administradores")) {
	// Mostramos el botón para borrar
	$SYS["delete_type"]="image";

	// Definimos la ruta de los iconos
	$SYS["delete_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/delete.png";
	$SYS["deactivate_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/deactivate.png";

} else {
	// Ocultamos el botón para borrar
	$SYS["delete_type"]="hidden";

	// Modificamos el icono del botón para desactivar
	$SYS["deactivate_icon_url"]=$SYS["ROOT"]."Apps/".$SYS["PROJECT"]."/local/Icons/delete.png";
}


plantHTML($SYS,"interface");

if(isset($_SESSION["search"])) {
	unset($_SESSION["search"]);
	unset($_SESSION["query"]);
	jsAction("getElementById('search_icon').src='<!-- D:ROOT -->Apps/<!-- D:PROJECT -->/local/Icons/search_enabled.png';");
}

if(isset($_SESSION["master"]))
	unset($_SESSION["master"]);

?>