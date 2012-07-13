<?php
  function sumaMsg(){
	global $prefix;
	
	$arr=_query("SELECT SUM(msg) FROM {$prefix}_{$this->name}");
	$res=_fetch_array($arr);
	$total=$res["SUM(msg)"];
        if($total==0)
	  $total=1;
	return $total;
  }

  function sumaMsg2(){//creada por javi para prueba
	global $prefix;
	
	$arr=_query("SELECT count(*) FROM {$prefix}_post;");
	$res=_fetch_array($arr);
	$total=$res["count(*)"];
        if($total==0)
	  $total=1;
	return $total;
  }

  function countForo(){
	global $prefix;
	
	$arr=_query("SELECT count(*) FROM {$prefix}_{$this->name}");
	$res=_fetch_array($arr);
	$total=$res["count(*)"];

	return $totalforo;
  }

  function listMsg($total) {
	
	$arr=$this->selectall();
	$ob=current($arr);
	
	for ($i=0,$j=sizeof($arr);$i<$j;$i++) {
	        $pc=($ob->msg / $total);
		$gindex[$ob->ID]=gfxbar($pc,"white","blue","center","50");
		$ob=next($arr);
	}
	return $gindex;
	
}

function msgBar($total) {

	$parcial=$this->msg;
	if($parcial==1)
	  $parcial=2;
	$pc=$parcial/$total;
	return gfxBarS($pc,"#FFBA31","white","center","100");
}
?>