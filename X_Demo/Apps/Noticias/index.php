<?php
require_once("Noticias.php");
$busqueda=(isset($_POST["busca"]))?$_POST["busca"]:$busqueda;
$cat_id=(isset($cat_id))?$cat_id:1;
setNavVars(array("cat_id","busqueda"));
$n=newObject("notice");
$n->isAdmin=BILO_isAdmin();
/*$us=newObject("user",BILO_uid());//BILO-uid nos da el id del usuario actual.
$us->searchResults=$us->select("ID = 'BILO_uid'");
//$_SESSION['fecha']=$us->fecha_hoy;
$_SESSION['fecha']=$us->fecha_hoy;*/
/*
if ($busqueda != "")
{
	$keys=explode(" ",$busqueda);
	foreach($keys as $k=>$v)
	{
		$q[]="(titulo LIKE '%$v%' OR body LIKE '%$v%' OR keyword LIKE '%$v%')";
		$xquery=implode("OR",$q);
	}
	
	$n->searchResults=$n->select($xquery,$offset,$sort);
	if($n->nRes==0)
		$n->nohay="NO EXISTEN NOTICIAS QUE CUMPLAN EL CRITERIO DE BÚSQUEDA";

}else
{
	if($cat_id>1)
	{
	
		$n->searchResults=$n->select("id_cat=$cat_id",$offset,$sort);
		if($n->nRes==0)
			$n->nohay="NO HAY NINGUNA NOTICIA EN ESTA CATEGORIA";
			
	
	}else
		$n->searchResults=$n->selectAll($offset,$sort);
		if($n->nRes==0)
			$n->nohay="NO HAY NINGUNA NOTICIA DISPONIBLE";
}
*/
if ($busqueda != "")
{

$n->searchResults=$n->selectAll($offset,$sort);
$n->searchResults=$n->select($n->buildMultiquery($busqueda),$offset,$sort);
if($n->nRes==0)
		$n->nohay="NO EXISTEN NOTICIAS QUE CUMPLAN EL CRITERIO DE BÚSQUEDA";
}else{
if($cat_id>1)
	{
	
		$n->searchResults=$n->select("id_cat=$cat_id",$offset,$sort);
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
plantHTML($n,'search_notice');
$c=newObject("cat_not",$cat_id);
$c->fecha_hoy=date(time());
plantHTML($c,'menu_cat',array("ID"=>$c->listAll("nombre_cat")));

listList($n,array(),'list_notice',"",1,"plParseTemplateFast");
?>