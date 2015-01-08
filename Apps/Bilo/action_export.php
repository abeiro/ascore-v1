<?php
require_once("Bilo.php");
set_include_dir(dirname(__FILE__) . "/../Ok/-");

if (BILO_isOperator()) {

    $u = newObject("user");

    $sort=(empty($sort))?"S_Date_C DESC":$sort;
    
    setLimitRows(15000);
    setNavVars(array("busca", "soloprofesores", "soloalumnos"));


    $u->searchResults = $u->select($_SESSION["last_user_query"][0], 0, $_SESSION["last_user_query"][1]);
		





    foreach ($u->searchResults as $idx => $us) {
        if ($us->inGroup("Profesores")) {
            $p = newObject("profesor");
            $p->getByUserId($us->ID);
			$u->searchResults[$idx]->supervisado = $p->supervisado;
             
        }
    }
    //FormAction("","","editForm");
    
    $u->isAdmin = BILO_isAdmin();

    //print_r($u->searchResults);
	while(@ob_end_clean());
	header("Content-Type: plain/text");
	header("Content-Disposition: attachment; filename=usuarios.csv");
	ob_start();
    listList($u, array("grupos_nombre" => "fref#user|ID|listGroupsNames"), "list_users_csv", "", 1, "plParseTemplateFast");
	$bdata=ob_get_contents();
	ob_end_clean();
	$bdata1=preg_replace('/<!--(.*)-->/',"",$bdata);
	$bdata2=preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $bdata1);
	echo str_replace ( " &nbsp;" ,"" , $bdata2);

}

resetLimitRows();
?>


