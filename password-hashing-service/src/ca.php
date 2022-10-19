<?php
/* CA Translation */

class Translator{
    
    public $text;
    public function translate() {
        $this -> translation = "Sorry, " . $this->text;
    } 

};