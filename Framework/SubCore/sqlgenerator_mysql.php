<?php

debug("Prefijo $prefix tabla $this->name", "red");
if ($GLOBALS["SYS"]["DBDRIVER"] == "mysql")
    $q = "SHOW TABLES";
else if ($GLOBALS["SYS"]["DBDRIVER"] == "postgres")
    $q = "SELECT tablename from pg_tables where tablename ILIKE '$prefix%'";
$bdres = _query($q);
$exists = False;

while ($rawres = _fetch_array($bdres)) {
    if (current($rawres) == "{$prefix}_{$this->name}")
        $exists = True;
}
if ($exists) {
    $q = "";
    /* La tabla  existe */
    if ($GLOBALS["SYS"]["DBDRIVER"] == "mysql")
        $q = "SHOW FIELDS FROM `{$prefix}_{$this->name}`";
    else if ($GLOBALS["SYS"]["DBDRIVER"] == "postgres")
        $q = "SELECT column_name FROM information_schema.columns WHERE table_name ='{$prefix}_{$this->name}'";
    $bdres = _query($q);
    $j = 0;
    while ($rawres = _fetch_array($bdres)) {
        $fieldlst[$j] = current($rawres);
        $j++;
    }
    $q = "";
    reset($this->properties_type);
    reset($this->properties);
    for ($i = 0, $loop_c = sizeof($this->properties); $i < $loop_c; $i++) {
        //echo "-".current($this->properties_type).".<br>";
        if (in_array(key($this->properties), $fieldlst))
            $action = "ALTER TABLE `{$prefix}_" . $this->name . "` CHANGE `" . key($this->properties) . "` `" . key($this->properties) . "` ";
        else
            $action = "ALTER TABLE `{$prefix}_" . $this->name . "` ADD `" . key($this->properties) . "` ";

        if (strstr(current($this->properties_type), "string")) {
            $len = explode(":", current($this->properties_type));
            $q.= $action . "VARCHAR( $len[1] ) NOT NULL ;\n";
        } else if (strstr(current($this->properties_type), "longtext")) {
            $len = explode(":", current($this->properties_type));
            $q.= $action . "BLOB  NOT NULL ;\n";
        } elseif (strstr(current($this->properties_type), "text")) {
            $q.=$action . " TEXT  NOT NULL ;\n";
        } elseif (strstr(current($this->properties_type), "password")) {
            $q.=$action . " TEXT  NOT NULL ;\n";
        } else if (strstr(current($this->properties_type), "list")) {
            $len = explode(":", current($this->properties_type));
            $options = explode("|", $len[1]);
            $enum = "'" . $options[0] . "'";
            for ($j = 1, $options_size = sizeof($options); $j < $options_size; $j++) {
                $enum.=",'" . $options[$j] . "'";
            }
            $q.=$action . " ENUM ( " . $enum . " ) NOT NULL;\n";
        } elseif (strstr(current($this->properties_type), "nulo")) {
            $q.="";
        }
        //------------------------------------------------------------------------------------------
        else if (strstr(current($this->properties_type), "xref")) {
            $q.=$action . " INT;\n";

            $xref = explode(":", current($this->properties_type));
            $table_name = $this->name . "_" . $xref[1];

            $field2 = $this->name . "_id";
            $field3 = $xref[1] . "_id";

            $q2 = "SHOW TABLES";
            $bdres = _query($q2);
            $exists = False;
            while ($rawres = _fetch_array($bdres)) {
                if (current($rawres) == "{$prefix}_{$table_name}")
                    $exists = True;
            }
            if (!$exists) { // Si no existe, creamos la tabla de referencias externas
                $q2 = "CREATE TABLE `{$prefix}_" . $table_name . "` (\n";
                $q2.="`ID` INT NOT NULL AUTO_INCREMENT ,\n";
                $q2.="`" . $field2 . "` INT NOT NULL ,\n";
                $q2.="`" . $field3 . "` INT NOT NULL ,\n";
                $q2.="INDEX ( `ID` ), PRIMARY KEY ( `ID` )\n)\n";
                $bdres = _query($q2);
                $warning = "La tabla de referencias externas creada anteriormente deberá ser borrada manualmente de la base de datos";
            }
        }

        //------------------------------------------------------------------------------------------
        else if (strstr(current($this->properties_type), "ref")) {
            $q.=$action . " INT;\n";
        } else if (strstr(current($this->properties_type), "int")) {
            $q.=$action . " INT;\n";
        } else if (strstr(current($this->properties_type), "date")) {
            $q.=$action . " INT;\n";
        } else if (strstr(current($this->properties_type), "datex")) {
            $q.=$action . " INT;\n";
        } else if (strstr(current($this->properties_type), "time")) {
            $q.=$action . " INT;\n";
        } else if (strstr(current($this->properties_type), "money")) {
            $q.=$action . " DECIMAL(15,5);\n";
        } else if (strstr(current($this->properties_type), "float")) {
            $q.=$action . " DECIMAL(15,5);\n";
        } else if (strstr(current($this->properties_type), "boolean")) {
            $len = explode(":", current($this->properties_type));
            $q.=$action . " ENUM('Si','No') ";
            $q.="DEFAULT '{$len[1]}' NOT NULL;\n";
        }

        next($this->properties_type);
        next($this->properties);
    }
    //      LIMPIEZA
    print_r($fieldlst);
    for ($i = 0, $fieldlst_options = sizeof($fieldlst); $i < $fieldlst_options; $i++)
        if ((in_array($fieldlst[$i], array_keys($this->properties))) || ($fieldlst[$i] == "ID"))
            echo "";
        else {
            $q.="ALTER TABLE `{$prefix}_{$this->name}` DROP `" . $fieldlst[$i] . "`;\n";
        }
    echo "<pre>$q</pre>";
    //------------------------------------------------------------------------------------------

    echo $warning;

    //------------------------------------------------------------------------------------------
    return $q;
} else {
    //------------------------------------------------------------------------------------------
    // Creamos previamente la tabla de referencias externas
    foreach ($this->properties as $pk => $pv) {
        if (strpos($this->properties_type[$pk], "xref") !== False) {
            $xref = explode(":", $this->properties_type[$pk]);
            $table_name = $this->name . "_" . $xref[1];


            $field2 = $this->name . "_id";
            $field3 = $xref[1] . "_id";

            $q = "CREATE TABLE `{$prefix}_" . $table_name . "` (\n";
            $q.="`ID` INT NOT NULL AUTO_INCREMENT ,\n";
            $q.="`" . $field2 . "` INT NOT NULL ,\n";
            $q.="`" . $field3 . "` INT NOT NULL ,\n";
            $q.="INDEX ( `ID` ), PRIMARY KEY ( `ID` )\n)\n";
            $bdres = _query($q);
        }
    }

    //------------------------------------------------------------------------------------------
    $q = "CREATE TABLE `{$prefix}_" . $this->name . "` (\n";
    $q.="`ID` INT NOT NULL AUTO_INCREMENT ,\n";
    reset($this->properties_type);
    reset($this->properties);
    for ($i = 0, $properties_size = sizeof($this->properties); $i < $properties_size; $i++) {
        //echo "-".current($this->properties_type).".<br>";
        if (strstr(current($this->properties_type), "string")) {
            $len = explode(":", current($this->properties_type));
            $q.="`" . key($this->properties) . "` VARCHAR( $len[1] ) NOT NULL ,\n";
        } else if (strstr(current($this->properties_type), "longtext")) {
            $len = explode(":", current($this->properties_type));
            $q.="`" . key($this->properties) . "` BLOB NOT NULL ,\n";
        } elseif (strstr(current($this->properties_type), "text")) {
            $q.="`" . key($this->properties) . "` TEXT  NOT NULL ,\n";
        } else if (strstr(current($this->properties_type), "list")) {
            $len = explode(":", current($this->properties_type));
            $options = explode("|", $len[1]);

            $enum = "'" . $options[0] . "'";
            for ($j = 1, $options_size = sizeof($options); $j < $options_size; $j++) {
                $enum.=",'" . $options[$j] . "'";
            }

            $q.="`" . key($this->properties) . "` ENUM ( " . $enum . " ) NOT NULL,\n";
        } else if (strstr(current($this->properties_type), "ref")) {
            $q.="`" . key($this->properties) . "` INT,\n";
        } else if (strstr(current($this->properties_type), "int")) {
            $q.="`" . key($this->properties) . "` INT,\n";
        } else if (strstr(current($this->properties_type), "date")) {
            $q.="`" . key($this->properties) . "` INT,\n";
        } else if (strstr(current($this->properties_type), "datex")) {
            $q.="`" . key($this->properties) . "` INT,\n";
        } else if (strstr(current($this->properties_type), "time")) {
            $q.="`" . key($this->properties) . "` INT,\n";
        } elseif (strstr(current($this->properties_type), "nulo")) {
            $q.="";
        } else if (strstr(current($this->properties_type), "money")) {
            $q.="`" . key($this->properties) . "` DECIMAL(15,5),\n";
        } else if (strstr(current($this->properties_type), "float")) {
            $q.="`" . key($this->properties) . "` DECIMAL(15,5),\n";
        } else if (strstr(current($this->properties_type), "boolean")) {
            $len = explode(":", current($this->properties_type));
            $q.="`" . key($this->properties) . "` ENUM('Si','No') ";
            $q.="DEFAULT '{$len[1]}' NOT NULL,\n";
        }
        next($this->properties_type);
        next($this->properties);
    }
    $q.="INDEX ( `ID` ), PRIMARY KEY ( `ID` )\n)\n";
    echo "<pre>$q</pre>";
    
}
?>
