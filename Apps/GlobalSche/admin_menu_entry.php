<?php
  
  /* Link, Frame, Label and Variable to check to show */
  
  $menu_entry=array(
    "label"=>"Planificador",
    "active"=>True,
    "items"=>array(
      array(
        array(
          array("GlobalSche/Master/task_list.php","fbody","Tareas"),
          array("GlobalSche/Master/task_add.php","fbody","Añadir Tareas"),
          array("GlobalSche/Master/step_list.php","fbody","Pasos"),
          array("GlobalSche/Master/step_add.php","fbody","Añadir Pasos"),
          array("GlobalSche/Master/schedule_list.php","fbody","Programaciones"),
          array("GlobalSche/Master/schedule_add.php","fbody","Añadir Programaciones"),
          array("GlobalSche/Master/tasklog_list.php","fbody","Registros"),
          array("GlobalSche/Master/tasklog_add.php","fbody","Editar Registros"),
          array("GlobalSche/Master/steplog_list.php","fbody","Registros de Pasos"),
          array("GlobalSche/Master/steplog_add.php","fbody","Editar Registros de Pasos")
        ),
        "Maestros","Maestros"
      ),
      array("GlobalSche/action_control.php","fbody","Panel de Control"),
        array("GlobalSche/action_monitor.php","fbody","Seguimiento"),
      array("GlobalSche/action_definitions.php","fbody","Definiciones"),
      array(
        array(
          array("GlobalSche/Reports/action_report_today.php","_blank","Informe - Hoy -"),
          array("GlobalSche/Reports/action_report_atrasadas.php","_blank","Informe - Atrasadas -"),
          array("GlobalSche/Reports/action_report_semanal.php","_blank","Informe - Semanal -"),        
        ),
        "Informes","Informes"
      )
      
    )
  );
  
  ?>