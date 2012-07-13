<?php
require_once("Noticias.php");

$c=newObject("cat_not",$ID);
formAction("action_save_cat.php","footer","editForm");
$c->boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);
plantHTML($c,'add_cat');
formClose();

?>