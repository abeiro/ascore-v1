<?php

require_once("Articulus.php");
$u=newObject("categoria");
setNavVars(array("ID","cat_id"));
$u->searchResults=$u->select("cat_id<2");
$filas=_affected_rows();
if ($filas>0)
listList($u,array(),"index");
else
  echo "No hay ninguna categoría disponible";


?>