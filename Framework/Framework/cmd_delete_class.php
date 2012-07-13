<?php

$dir=$SYS["BASE"]."/Apps/".$SYS["PROJECT"]."/local/Class/";
$file=$dir.$class.".def";



if(unlink($file)){

	echo "Borrado con יxtio";
	
}else{
	echo "La clase no ha sido borrada";

}

	header("Location: {$SYS["ROOT"]}/Backend/{$SYS["PROJECT"]}/dev.php?");
	die();

?>