<?php

class asiCal {
    var $events=array();
    
    public function __construct() {

    }
    
    public function addEvent($event) {

            $this->events[]=$event;
    }
    
    public function render() {

        $buffer="BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
";
        
        foreach ($this->events as $singleEvent) {
            $buffer.="BEGIN:VEVENT
UID:" . md5($singleEvent["uid"]) . "@gesclases.com
DTSTAMP:" . gmdate('Ymd',$singleEvent["start"]).'T'. gmdate('His',$singleEvent["start"]) . "Z
DTSTART:" . gmdate('Ymd',$singleEvent["start"]).'T'. gmdate('His',$singleEvent["start"]) . "Z
DTEND:" . gmdate('Ymd',$singleEvent["end"]).'T'. gmdate('His',$singleEvent["end"]) . "Z
SUMMARY:{$singleEvent["subject"]}
END:VEVENT
";
            
        }
        
        $buffer.="END:VCALENDAR";
      
        return  $buffer;
        
    }
    
}
?>
