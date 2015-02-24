<?php
/* 
Simple wrapper para trabajar con frames

*/


/*
Recarga un frame
*/

function frameReload($frame) {
	echo '

<script type="text/javascript" language="JavaScript1.3">
	parent.'.$frame.'.location.reload();
</script>

	';
}

/*
Resetea un frame
*/

function frameReset($frame) {
	echo '

<script type="text/javascript" language="JavaScript1.3">
	parent.'.$frame.'.location.href="about:blank";
</script>

	';
}

/*
Manda una operaci�n a un frame
*/

function frameSendAction($frame,$action) {
	echo '

<script type="text/javascript" language="JavaScript1.3">
	parent.'.$frame.'.'.$action.';
</script>

	';
}

/*
Retrodece el historial de un frame
*/

function frameBack($frame) {
	echo '

<script type="text/javascript" language="JavaScript1.3">
	parent.'.$frame.'.history.back();
</script>

	';
}
/*
Manda un link al frame
*/
function frameGo($frame,$url) {
	echo '

<script type="text/javascript" language="JavaScript1.3">
	parent.'.$frame.'.location.href="'.$url.'";
</script>

	';
}

/*
Escribe texto en el frame
*/
function frameWrite($frame,$text) {
	global $SYS;
	echo '

<script type="text/javascript" language="JavaScript1.3">
	parent.'.$frame.'.document.write(\''.$text.'\');
</script>

	';
}

/*
Escribe texto en un textarea del frame
*/
function frameWriteTextA($frame,$text) {
	echo '

<script type="text/javascript" language="JavaScript1.3">
	o=parent.'.$frame.'.document.getElementById("msgWindow");
	o.value=\''.$text.'\';
</script>

	';
}
/*
Send operation to frame
*/

function jsAction($action) {
	echo '

<script type="text/javascript" language="JavaScript1.3">
	'.$action.';
</script>

	';
}
?>
