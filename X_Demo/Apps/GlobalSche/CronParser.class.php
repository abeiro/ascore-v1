<?php

/**
 * Cron schedule parser 
 */
class Parser {

    var $pstring = "";
    var $datesToday = array();
    var $parsedData = array();

    function __construct($str) {

        $pstrin = $str;
        $chunk = explode(" ", $str);

        $fchunk["min"] = $chunk[0];
        $fchunk["hour"] = $chunk[1];
        $fchunk["dom"] = $chunk[2];
        $fchunk["mon"] = $chunk[3];
        $fchunk["dow"] = $chunk[4];

        foreach ($fchunk as $k => $c) {
            if (strpos($c, "-") > 0) {
                $a = substr($c, strpos($c, "-") - 1, 1);
                $b = substr($c, strpos($c, "-") + 1, 1);
                for ($i = $a + 1; $i < $b; $i++)
                    $expanded.=",$i";
                $d = str_replace("-", "$expanded,", $c);
                $c = $d;
            }

            $subchunk[$k] = explode(",", $c);
        }

        if ($subchunk["hour"][0] == "*") {
            $subchunk["hour"] = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23);
        }
        if ($subchunk["minute"][0] == "*") {
            for ($i = 0; $i < 60; $i++)
                $subchunk["minute"][] = $i;
        }
        $this->parsedData = $subchunk;
        //print_r($this->parsedData);
    }

    function GetRuns($tstamp) {

        $parsedData = $this->parsedData;

        foreach ($parsedData["dow"] as $dayofweek) {
            /* Patch */
            if ($dayofweek == 7)
                $dayofweek = 0;
            if (($dayofweek === '*') || ($dayofweek == date("w", $tstamp)))
                foreach ($parsedData["mon"] as $monthofyear)
                    if (($monthofyear === '*') || ($monthofyear == date("n", $tstamp)))
                        foreach ($parsedData["dom"] as $dayofmonth)
                            if (($dayofmonth === '*') || ($dayofmonth == date("j", $tstamp))) {
                                debug("Execution 4 today!", "green");
                                foreach ($parsedData["hour"] as $hora)
                                    foreach ($parsedData["min"] as $minuto)
                                        $result[] = mktime($hora, $minuto, 1, date("n", $tstamp), date("j", $tstamp), date("Y", $tstamp));
                            }
        }


        return $result;
    }

}

//$p = new Parser("0 12 * * 7");
//print_r($p->parsedData);
?>
