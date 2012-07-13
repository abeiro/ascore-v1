<?php

require_once("HTML2FPDF/html2fpdf.php");
require_once("HTML2FPDF/htmltoolkit.php");
	
function PDF_html_2_pdf($data) {
	global $auth;
	
	
	
	$pdf=new HTML2FPDF();
	$pdf->UseCSS();
	$pdf->UseTableHeader();
	$pdf->AddPage();
	$pdf->WriteHTML(AdjustHTML($data));
	$pdf->Output();
}
?>