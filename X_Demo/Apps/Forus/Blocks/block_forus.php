<?php

require_once("Forus/Forus.php");

/* Bloques */

function FORUS_block_forus() {
	
	global $SYS;
	$c=newObject("categoria");
	
	$c->searchResults=$c->select("nombre='faqs'",$offset,$sort);
	if($c->nRes > 0)
		$faq=$c->searchResults[0]->ID;
		

	if(BILO_isLogged())
		$clase="login";
	else
		$clase="logoff";
	return "<table  border=\"0\"  width=\"127\" cellspacing=\"0\" class=\"block\"><tr><th class=\"$clase\">Soporte Linux
	</td></tr><tr><td class=\"boton\"><a style=\"text-decoration:none;\" href=\"{$SYS["ROOT"]}/Forus/Lista_Foros/\">Foros</a></td></tr><td class=\"boton\"><a style=\"text-decoration:none;\" href=\"{$SYS["ROOT"]}/Articulus/Lista/cat_id=$faq/\">Faqs</a></td></tr></table>";
	}

?>