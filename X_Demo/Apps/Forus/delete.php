<?php

require_once("Forus.php");
HTML("action_header");
//echo "Borrando....";
$ID=(isset($ID))?$ID:1;
$p_id=(isset($p_id))?$p_id:0;
$foro_id=(isset($p_id))?$p_id:1;

$d=newObject("post",$ID);
$e=newObject("foro",$d->foro_id);
$f=newObject("post",$p_id);
if($p_id==0){
$e->msg=$e->msg - ($d->respuestas+1);
$e->save();

}else{
  $e->msg--;
  $f->respuestas--;
  $e->save();
  $f->save();
 }
 
 $d->deletes("p_id=$ID");
 $d->delete();

echo "Borrado ok";
frameReload("fbody");

if ($d->p_id > 1) {
      
	frameGo("body",'view.php?ID='.$d->p_id.'&foro_id='.$d->foro_id);
}
else
	frameGo("body",'index2.php?foro_id='.$d->foro_id);	



	
?>