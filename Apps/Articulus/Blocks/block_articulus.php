<?php
require("Articulus/Articulus.php");
function ART_block_biblio()
{
global $SYS;
$c=newObject("categoria");
$c->searchResults=$c->select("cat_id=0 AND nombre <> 'faqs'",$offset,$sort);
ob_start();


listList($c,array(),"Articulus/biblio");
//plantHTML($SYS,"Articulus/biblio");

$data=ob_get_contents();
ob_end_clean();
return $data;

}