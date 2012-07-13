<?php

/*

PROYECTO exmaple

*/

define(_DEFAULT,1);
define(_NONE,0);
define(_LOGOS,2);
define(_NOTICIAS,3);
define(_FOTOARTS,4);
define(_DOCOARTS,5);
define(_OLD,6);
define(_FILES,7);
define(_SOFTWARE,8);


$FAMILYLABEL=array(
	_DEFAULT=>"./",
	_NONE=>"./",
	_LOGOS=>"./Logos/",
	_SOFTWARE=>"./Software/",
	_NOTICIAS=>"./Noticias/",
	_FOTOARTS=>"./Fotos_Articulos/",
	_DOCOARTS=>"./Documentos_Articulos/",
	_OLD=>"./Old/",
	_FILES=>"./Files/",
	
);

/* Plantillas */
set_include_dir(dirname(__FILE__)."/../local/Tmpl/-");


$SYS["DBDRIVER"]="mysql";

?>