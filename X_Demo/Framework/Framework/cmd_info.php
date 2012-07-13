<?php
$info=new core();
?>

<h1 align="center">Core G2 </h1>

<div align="center">Version : <?php echo $info->version();?><br>
Fecha: <?php echo strftime("%V/%m/%Y",$info->release());?></div>

