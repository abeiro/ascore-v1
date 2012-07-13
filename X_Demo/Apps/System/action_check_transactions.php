<?php

require_once("System.php");
HTML("action_header");

if (_query("create table coreg2_tran_test (a int, b int) type = InnoDB"))
	echo _("tabla InnodDB creada :: ");
else
	die(_("Error creando tabla InnodDB"));

_query("begin") or die(_("begin")); 
_query("insert into coreg2_tran_test (a,b) values (1,2)") or die(_("insert into coreg2_tran_test (a,b) values (1,2)"));
echo _("Columnas insertadas "._affected_rows())." :: ";
_query("select * from coreg2_tran_test") or die(_("Error select * from coreg2_tran_test"));
_query("rollback") or die(_("rollback")); 
_query("select * from coreg2_tran_test") or die(_("Error select * from coreg2_tran_test"));
_query("DROP table coreg2_tran_test ") or die(_("asesinato frustrado"));
echo " [OK]"; 


?>


