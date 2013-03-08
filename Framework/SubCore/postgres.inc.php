<?php

define("DBOPERAND_ILIKE", "ILIKE");

$dbhost = $SYS["postgres"]["DBHOST"];
$dbuser = $SYS["postgres"]["DBUSER"];
$dbpass = $SYS["postgres"]["DBPASS"];
$dbname = $SYS["postgres"]["DBNAME"];
$dbport = ($SYS["postgres"]["DBPORT"]) ? ($SYS["postgres"]["DBPORT"]) : "5432";


$GLOBALS["pgconnection"] = pg_connect("host=$dbhost port=$dbport dbname=$dbname user=$dbuser password=$dbpass")
        or debug(_("Postgres Driver: Failed connect: " . pg_errormessage($GLOBALS["pgconnection"])), "red");

debug(_("Postgres Driver: Connected succesfully") . ":encoding: " . pg_client_encoding($GLOBALS["pgconnection"]), "green");
pg_set_client_encoding($GLOBALS["pgconnection"], "UNICODE");
debug(_("Postgres Driver: Connected succesfully") . ":encoding: " . pg_client_encoding($GLOBALS["pgconnection"]), "green");
debug(print_r(pg_version($GLOBALS["pgconnection"]), true), "green");

function qdie($msg) {

    if ($GLOBALS["sqlexceptions"]) {
        print($msg);
        throw new Exception;
    } else {
        die($msg);
    }
}

function _query($q, $multi = False) {



    if ($multi) {
        $sentences = array();
        $sentences = explode(";", $q);

        for ($i = 0; $i < sizeof($sentences); $i++) {
            $line = trim($sentences[$i]);
            if (empty($line))
                continue;
            $q = __pg_translate($sentences[$i]);
            $GLOBALS["__pglastres"] = pg_query($GLOBALS["pgconnection"], $q) or print("query failed: [\"" . $q . "\"]" . pg_errormessage($GLOBALS["pgconnection"]));
        }
    }

    else {
        if ($q == "SELECT FOUND_ROWS()") {
            debug("Postgres Driver:SELECT " . pg_num_rows($GLOBALS["__pglastres"]) . " as FOUND_ROWS()", "yellow");
            $lastRowres = pg_num_rows($GLOBALS["__pglastres"]);
            return pg_query($GLOBALS["pgconnection"], "SELECT $lastRowres as \"FOUND_ROWS()\"");
        } else {
            $q = __pg_translate($q);
            $GLOBALS["__pglastres"] = pg_query($GLOBALS["pgconnection"], $q) or qdie(debug("query failed: [\"$q\"]" . pg_errormessage($GLOBALS["pgconnection"]), "red"));
        }
    }
    debug(" SQL: " . $q . " Rows:" . pg_num_rows($GLOBALS["__pglastres"]), "green");
    return $GLOBALS["__pglastres"];
}

function _fetch_array($bdid) {

    return pg_fetch_assoc($bdid);
}

function _last_id() {
    //$lastId=current(pg_fetch_assoc($GLOBALS["__pglastres"]));
    /* This is very wrong. You should use postgres 8.2 or above */
    debug(" SQL: SELECT MAX(\"ID\") as lid FROM {$GLOBALS["SYS"]["LASTPGTABLE"]}", "green");
    $pres = pg_query($GLOBALS["pgconnection"], "SELECT MAX(\"ID\") as lid FROM {$GLOBALS["SYS"]["LASTPGTABLE"]}");
    $data = pg_fetch_assoc($pres);
    $lastId = $data["lid"];
    debug("POSTGRESQL last inserted id $lastId", "red");
    return $lastId;
}

function _affected_rows() {

    return ((pg_affected_rows($GLOBALS["__pglastres"]) > 0) ? pg_affected_rows($GLOBALS["__pglastres"]) : pg_num_rows($GLOBALS["__pglastres"]));
}

function __pg_translate($sqlcode) {
    $q = str_replace("SQL_CALC_FOUND_ROWS", "", $sqlcode);
    $q = preg_replace('/LIMIT ([0-9]+),([0-9]+)/', 'LIMIT \2 OFFSET \1', $q);
    //$q = preg_replace('/ORDER BY W/', 'LIMIT \2 OFFSET \1', $q);
    $q = strtr($q, array("`" => '"'));

    return $q;
}

?>
