<?php

require_once("Articulus.php");
plantHTML(array(),'action_header');
echo $_POST["ID"];
if ($_POST["ID"]>1) {
  $form=newObject("documento",$ID);
  $form->setAll($_POST);
  $cat=newObject("categoria",$form->cat_id);
  $form->cat_pr=$cat->cat_pr;
  //echo $cat->cat_pr;
  $form->cuerpo=htmlentities($_POST['editor']);
  $form->fecha_mod=time();
  
  if ($form->save())
    echo "Guardado";

}
else
{
  $form=newObject("documento");
  $form->setAll($_POST);
  $form->cat_id=$_POST["cat_id"];
  $cat=newObject("categoria",$form->cat_id);
  $form->cat_pr=$cat->cat_pr;
  //echo $cat->cat_pr;
  $form->cuerpo=htmlentities($_POST['editor']);
  $form->fecha_mod=time();
  $form->fecha_cre=time();
  
        if ($form->save())
    echo "Guardado";
  
}

HTML("action_footer");
frameGo("fbody",'index2.php');



?>