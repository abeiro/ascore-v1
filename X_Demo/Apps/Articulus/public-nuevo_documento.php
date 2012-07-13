<?php

require_once("Articulus.php");
HTML('menu_doc');
$ID=(isset($ID))?$ID:1;
$p=newObject("documento",$ID);

$p->cat_id=(isset($cat_id))?$cat_id:1;

formAction("save_documento.php","footer","editForm");

$p->boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);
plantHTML($p,"documento");
formClose();
plantHTML($_GET,"view_docu");
?>