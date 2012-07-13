<?php
require_once("Biblio.php");

$c=newObject("cat_lib",$ID);
formAction("action_save_catlib.php","footer","editForm");
$c->boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);
plantHTML($c,'add_catlib');
formClose();

?>