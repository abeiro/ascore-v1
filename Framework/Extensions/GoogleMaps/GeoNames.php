<?php

$LAT=array("N","37","22","38");
$LON=array("W","5","59","13");
$SIG=array("N"=>1,"S"=>-1,"W"=>-1,"E"=>1);
$A=((($LAT[3]*100/60)/10000)+(($LAT[2]*100/60)/100)+$LAT[1])*$SIG[$LAT[0]];
$B=((($LON[3]*100/60)/10000)+(($LON[2]*100/60)/100)+$LON[1])*$SIG[$LON[0]];

//echo "$A,$B";

echo "http://www.geonames.org/maps/google_37.3772222_-5.9869444.html<br>";
echo "<a href=\"http://www.geonames.org/maps/google_{$A}_{$B}.html\">http://www.geonames.org/maps/google_{$A}_{$B}.html</a><br>";

?>

