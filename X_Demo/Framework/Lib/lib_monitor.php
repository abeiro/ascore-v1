<?php
    /* Functions in this file */
    /**************************/

    // MonClose()
    // MonInit()
    // MonRead()
    // MonWrite($data)

if ($SYS["monitor_enabled"]) {

?>
<?php
class Monitor {

    var $shm_id=0;
    var $SYSPERFOMANCE;
    // Creacion de un segmento de memoria compartida de 100 bytes y con un
    // identificador igual a 0xff3
    function MonInit()
    {

	
        $this->$shm_id = shmop_open(0xff3, "c", 0644, 1024);
        if (!$this->$shm_id)
        {
            debug("No se pudo crear el segmento de memoria compartida\n", "red");
        }
        // Obtencion del tama&ntilde;o del segmento de memoria compartida
        $shm_size = shmop_size($this->$shm_id);
        debug("Segmento de memoria: se han reservado ".$shm_size. " bytes.\n", "blue");
    }
    function MonWrite($data)
    {
        // Escritura de una cadena de texto de prueba en la memoria compartida
        $shm_bytes_written = shmop_write($this->$shm_id, $data, 0);
        if ($shm_bytes_written != strlen($data))
        {
            debug("No se pudieron escribir todos los datos indicados\n", "red");
        }
	else
	    debug("Saved $data\n", "magenta");
    }
    // Lectura de la cadena de texto de prueba
    function MonRead()
    {
        $my_string = shmop_read($this->$shm_id, 0, $shm_size);
        if (!$my_string)
        {
            debug("No se pudo leer el segmento de memoria compartida\n", "red");
        }
        return $my_string;
    }
    // Borrado y eliminacion del segmento de memoria compartida
    function MonClose()
    {
        if (!shmop_delete($this->$shm_id))
        {
            debug("No se pudo borrar el segmento de memoria compartida.", "red");
        }
        shmop_close($this->$shm_id);
    }
 
    function MonAverageUpdate($time) {
	$this->SYSPERFOMANCE["pages"]++;
        $this->SYSPERFOMANCE["time"]+=$time;
	$this->MonWrite(serialize($this->SYSPERFOMANCE));

    }
   function MonGetStat() {
	$this->SYSPERFOMANCE["avg"]=$this->SYSPERFOMANCE["time"]/$this->SYSPERFOMANCE["pages"];
	return $this->SYSPERFOMANCE;
   }

}
	$monitor=new Monitor();
	$monitor->MonInit();
	$monitor->SYSPERFOMANCE=unserialize($monitor->MonRead());


}
?>
