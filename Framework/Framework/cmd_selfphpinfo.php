<?php
$buffer=file($SYS["ROOT"]."/dev.php?command=phpinfo&nodebug=True");
echo implode("\n",$buffer);

?>
