<?php

require_once("Biblio.php");
$busqueda=(isset($_POST["busca"]))?$_POST["busca"]:$busqueda;
$cat_id=(isset($cat_id))?$cat_id:1;
setNavVars(array("cat_id","busqueda"));
$n->isAdmin=BILO_isAdmin();

$n=newObject("libro");

setLimitRows(5);
$n->searchResults=$n->selectAll($offset,$sort);
$external_data=array(
		"loquesea"=>$n->get_external_reference("categoria")
);

if ($_POST["busca"]!=$_GET["busqueda"]){
//para iniciar el paginado
$offset=0;
}

if ($busqueda != "")
{

$n->searchResults=$n->selectAll($offset,$sort);
$n->searchResults=$n->select($n->buildMultiquery($busqueda),$offset,$sort);
if($n->nRes==0)
		$n->nohay="NO EXISTEN NOTICIAS QUE CUMPLAN EL CRITERIO DE BÚSQUEDA";
}else{
if($cat_id>1)
	{
		$n->searchResults=$n->select("nombre_cat=$cat_id",$offset,$sort);
		if($n->nRes==0)
			$n->nohay="NO HAY NINGUNA NOTICIA EN ESTA CATEGORIA";
			
	
	}else
		$n->searchResults=$n->selectAll($offset,$sort);
		if($n->nRes==0)
			$n->nohay="NO HAY NINGUNA NOTICIA DISPONIBLE";
}



formAction("","","editForm");
$n->boton0=gfxBotonAction("Buscar","getElementById('editForm').submit()",True);
$n->isAdmin=BILO_isAdmin();
$n->busca=$busqueda;
plantHTML($n,'search_lib');
$c=newObject("cat_lib",$cat_id);
$c->fecha_hoy=date(time());
plantHTML($c,'menu_lib',array("ID"=>$c->listAll("nombre_cat")));

listList($n,$external_data,"listadolibro");

//$libro->searchResults=$libro->selectAll($offset,$sort);
//listList($libro,array(),"listadolibro");

	/*$external_data=array(
		"categoria"=>"xref#categoria|cat_id|nombre_cat");
$v->isAdmin=BILO_isAdmin();
listList($v,$external_data,"listadolibro");*/

resetLimitRows();

/*$u->isAdmin=BILO_isAdmin();
	$u->searchResults=$u->select("1=1",$offset,$sort,'','',$OID);
	listList($u,array("grupos_nombre"=>"fref#user|ID|listGroupsNames"),"list_users","",1,"plParseTemplateFast");*/



?>
