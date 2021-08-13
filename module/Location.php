<?php

/**
 * Upravuje promene a nastavuje databazi pro lokalni pouziti
 * 
 * @author Petr
 */
class Location {
    
    private $folder = ""; // zmenit dle potreby
    private $base_folder = "";
    private $local = false;

    public function __construct() {
        if($_SERVER['SERVER_NAME'] === "localhost"){
            $this->local = true;
        }
    }
    
    public function setDb() {
        if($this->local){
            // prihlasovaci udaje pro localhost
            Db::connect('', '', '', '');
        }
        else { 
           // prihlasovaci udaje pro produkci  
           Db::connect('','','',''); 
        }
    }
        
    public function getFolder() {
        if($this->local){
            return $this->folder;
        }
        else { return $this->base_folder; }
    }
    
    public function getLocal() {
        return $this->local;
    }
    
}
