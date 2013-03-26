<?php

require_once("a2t.php");




 function getColumn($n,$data) {
  $c=0;
  $unkeyedarraykeys=array_keys(($data[0]));
  $result[$c++]=$unkeyedarraykeys[$n];
  foreach ($data as $row) {
    $unkeyedarray=array_values(($row));
    
    $result[$c++]=$unkeyedarray[$n];
  }

  return $result;
}


function rotateMatrix($matrix) {
 

  $dKeys[]=key(($matrix[0]));
  foreach ($matrix as $i=>$row) {
    $dKeys[]=current($row);
  }

  for ($j=1;$j<count($matrix[0]);$j++) {
    $transMatrix[]=array_combine($dKeys,getColumn($j,$matrix));
  }

  return $transMatrix;
}



?>



<?php
$bdres=_query("SELECT titulo,autor FROM  `coreg2_documento` WHERE ID>1");

while($customData=_fetch_array($bdres))
  $origMatrix[]=$customData;

echo array2table($origMatrix);
echo array2table(rotateMatrix($origMatrix));

?>