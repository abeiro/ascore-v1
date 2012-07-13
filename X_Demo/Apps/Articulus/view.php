<?php

require_once("Articulus.php");


$ID=(isset($ID))?$ID:1;
$p=newObject("documento",$ID);
$p->cuerpo=html_entity_decode($p->cuerpo);
plantHTML($p,"view");


  
?>