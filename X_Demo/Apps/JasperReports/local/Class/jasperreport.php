<?php
function Is_Print()
{
	if($this->printable=="No")
		$print=false;
	else	
		$print=true;
		
	return $print;
}

function hook_renderEditForm() {

	if(!isset($_SESSION["search_form"])) {
		unset($this->_arrayForm["form"]);

		if($this->ID != 1)
			$this->_boton0=gfxBotonAction("Llamar","getElementById('jasperreport').action='public_call_report.php',getElementById('jasperreport').submit();getElementById('jasperreport').action='public_w_operation_save.php'",True);
	}
}

?>