<?php
function ListCat()
{
	$this->searchResults=$this->select("cat_id =0",$offset,$sort);
	$i=0;
	foreach($this->searchResults as $key)
	{
		$data[$key->ID]=$key->nombre;
		$i++;
	
	}
	return $data;

}
function ListCatArt($cat_id)
{
	$this->searchResults=$this->select("cat_id =$cat_id OR ID=$cat_id",$offset,$sort);
	foreach($this->searchResults as $key)
	{
		$data[$key->ID]=$key->nombre;
		$i++;
	
	}
	return $data;

}

function Selsubtree()
{
	$c=newObject("categoria");
	$c->searchResults=$c->select("cat_id={$this->ID}",$offset,$sort);
	if($c->nRes>0)
		return true;
	else
		return false;
}


function subTree()
{
	$c=newObject("categoria");
	$c->searchResults=$c->select("cat_id=0",$offset,$sort);
	$i=0;
	foreach($c->searchResults as $key)
	{
		$i=$key->ID;
		$temp[]=$key;
		$cat=newObject("categoria");
		$cat->searchResults=$cat->select("cat_id=$i");
		foreach($cat->searchResults as $fila)
		{
			$temp[]=$fila;
		}
		
	
	}

	return $temp;
}	
	
function SepTree()
{	
	$rmax=$this->Rowstree();
	if($this->cat_id >1)
	{
		while($i<$rmax)
		{
			$tree.=	"&nbsp;&nbsp;";
			
		}
		$tree.=($this->cat_id>1)?"|_&nbsp;":"";
		}
	return $tree;

}

function RowsTree()
{
	
	$fin=false;
	$cat_id=$this->cat_id;
	$n=0;
	while($fin==false)
	{
		$c=newObject("categoria",$cat_id);
		if($c->cat_id>0)
		{
			$n++;
		}else
			$fin=true;
	}
	return $n;
}


function MdP(&$res,$link="",$max=0) {

	
	if ($max>4)
		return true;
		
	$p=newObject("categoria",$this->cat_id,$max++);
	$res=" <a  href=\"$link&cat_id=0\" style=\"text-decoration:none;\">Inicio</a>";
	
		
	if ($p->ID<2) {
		$res.="-&#062;<a href=\"$link&cat_id={$this->ID}\" style=\"text-decoration:none;\">{$this->nombre}</a>";
		return true;
	}
	else {
		
		$p->MdP(&$res,$link,$max);
		$res.="-&#062;<a href=\"$link&cat_id={$this->ID}\" style=\"text-decoration:none;\">{$this->nombre}</a>";
	}
	
	return true;
}
function PublicMainMdP(&$res,$link="",$max=0) {

	
	if ($max>15)
		return true;
		
	$p=newObject("categoria",$this->cat_id,$max++);
	$res.="<a class=\"minimal\" href=\"$link/\" style=\"text-decoration:none;\">Inicio</a>";
		return true;
	
	
}


function PublicMdP(&$res,$link="",$max=0) {

	
	if ($max>15)
		return true;
		
	$p=newObject("categoria",$this->cat_id,$max++);
	$res="<a class=\"minimal\" href=\"$link/cat=true/\" style=\"text-decoration:none;\">Categorias</a>->";
	
		
	if ($p->ID<2) {
		$res.="<a class=\"minimal\" href=\"$link/cat_id={$this->ID}/\" style=\"text-decoration:none;\">{$this->nombre}</a>";
		return true;
	}
	else {
		
		$p->PublicMdP(&$res,$link,$max);
		$res.="-&#062;<a class=\"minimal\" href=\"$link/cat_id={$this->ID}/\" style=\"text-decoration:none;\">{$this->nombre}</a>";
	}
	
	return true;
}

?>