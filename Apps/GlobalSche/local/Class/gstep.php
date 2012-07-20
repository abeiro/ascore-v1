<?php

function save() {
    global $prefix;
    debug("Info: Calling Extended save");
    if ($this->gtask_id < 2) {
        $this->ERROR = "No hay tarea seleccionada";
        return false;
    }

    $par = new Ente($this->name);
    $par = typecast($this, "Ente");
    //dataDump($par);
    return $par->save();
}

function run() {

    set_time_limit(0);
    /* Accion */
    $con = newObject("ghost", $this->host);
    $connection = $con->makeConnection();
    if ($connection) {
        // <tipo type="list" option="Ejecución|Espera">Tipo</tipo>
        switch ($this->tipo) {

            case 'Ejecucion':

                return $this->_run_command($connection);
                break;

            case 'Espera':
                $START_TIME = time();
                while (!$this->_waitfor($connection)) {
                    sleep(30);
                    if ((time() - TASK_TIMEOUT) > $START_TIME) {
                        /* Timed out */
                            return false;
                        
                    }
                }

                
                break;

            case 'Crear Script':

                return $this->_scripting($connection);

                break;
        }
    } else {
        $this->ERROR = "Error al ejecutar el comando";
        return false;
    }

    return true;
}

function _run_command($connection) {

    debug("Llamando a _run_command: ".__FILE__,"green");
    $fechadeevaluacion = time();
    $comando = "cd {$this->path};{$this->comando}";
    $comando = evaluateExpression($comando,$fechadeevaluacion,true);
    $comando = strtr($comando, "\n", " ");

    $hostConf = newObject("ghost", $this->host);
    if (strlen($hostConf->precomando) > 0)
        $comando = "{$hostConf->precomando};$comando";

    debug("$comando", "blue");
    if (!($stream = ssh2_exec($connection, "$comando; echo \"INT_RES_STATUS:$?\""))) {
        $this->ERROR = "Error al ejecutar el comando";
        return false;
    } else {
        stream_set_blocking($stream, true);
        $data = "";
        while ($buf = fread($stream, 4096)) {
            $data .= $buf;
        }
        fclose($stream);
        $this->TMPOUTPUT = $data;
    }
    $res = trim(substr($data, strpos($data, "INT_RES_STATUS") + 15));

    if ($this->maxreturnstatus) {
        $valores_de_retorno_admitidos = explode(",", $this->maxreturnstatus);
        if (in_array($res, $valores_de_retorno_admitidos))
            $res = 0;
    }

    if ($res != "0") {
        $this->ERROR = "Error al ejecutar el comando $comando\n ERROR CODE:{$data}";
        return false;
    } else {
        $this->ERROR = "$res";
        return true;
    }
}

function _waitfor($connection) {

    $fechadeevaluacion = time();
    $filetowaitfor = evaluateExpression("{$this->path}/{$this->ficheros}", $fechadeevaluacion,true);
    
    $fp =$filetowaitfor;


    $comando = "if [ -f $fp ]; then echo true;else echo false;fi";
    if (!($stream = ssh2_exec($connection, $comando))) {
        $this->ERROR = "Error al ejecutar el comando";
        return false;
    } else {
        stream_set_blocking($stream, true);
        $data = "";
        while ($buf = fread($stream, 4096)) {
            $data .= $buf;
        }
        fclose($stream);
    }

    if (trim($data) == "true") {
        $this->ERROR = "$fp existe";
        if (strlen($this->comando)>0)
                return $this->_run_command($connection);
        return true;
    } else {
        $this->ERROR = "$fp no existe ($data)";
        return false;
    }
}

function _scripting($connection) {

    $fichero_destino = evaluateExpression("{$this->path}/{$this->ficheros}",time(),true);

    /* Sustitucion de variables */

    $script = evaluateExpression($this->script, time(),false);
    
    debug($script, "blue");

    /**/

    $localfile = "/tmp/" . md5(time() . serialize($_SESSION) . rand(0, 1000));
    $f_localfile = fopen($localfile, "wb");
    fwrite($f_localfile, $script);
    fclose($f_localfile);
    chmod($localfile, 0755);

    if (!ssh2_scp_send($connection, $localfile, $fichero_destino)) {
        $this->ERROR = "Error al copiar el script $localfile,$fichero_destino";
        return false;
    }

    if ($this->comando) {

        return $this->_run_command($connection);
    }

    return true;
}

?>