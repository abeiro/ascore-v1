<?php
header("Content-Type: text/plain");
$test=new Ente($class);
echo $test->makeViewTemplate("test");

?>

