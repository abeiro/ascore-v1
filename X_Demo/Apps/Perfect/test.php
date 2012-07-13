<?php

require_once("Perfect.php");

$element=newObject("twar",2);
$element->loadFormXml(file_get_contents(dirname(__FILE__)."/local/Form/twar2.xml"));
echo $element->formMakeEditTemplate($element->_arrayForm,"prueba");
//dataDump($element);