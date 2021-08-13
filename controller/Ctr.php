<?php

/**
 * Description of Kontroler
 *
 * @author Petr
 */
abstract class Ctr {
    
    protected $controller;
    protected $folder;
    protected $islocal = false;
    protected $base_link = "online-task/task";
    //protected $db_tools;
    protected $data;
    protected $id_user;
    //protected $path;


    public function __construct() {
        $local = new Location();
        //$this->db_tools = new DbTools();
        $this->folder = $local->getFolder();
        $this->islocal = $local->getLocal();
    }
    
    
    protected function editLink($link) {
        return $this->folder . $link;
    }
    
    protected function className($text) {
	$str = str_replace('-', ' ', $text);
	$str = ucwords($str);
	$str = str_replace(' ', '', $str);
	return $str;
    }
    
    public function correctionLink($pos) {
        $str = '';
        $count_folder = count($this->data['parametr'])-1;
        if($count_folder>$pos){
            $count_folder = $count_folder - $pos;
            for($i=0; $i<$count_folder;$i++){
                $str .= '../';
            }
        }
        return $str;
    }
    
    public function isLogged($target = false) {
        if(isset($_SESSION['id']) && is_numeric($_SESSION['id'])){
            $this->id_user = $_SESSION['id'];
            $this->data['user_link'] = $this->correctionLink(1).'profile';
            $this->data['user_link_text'] = '<span class="glyphicon glyphicon-user"></span> '.$_SESSION['name'];
        }
        else{
            if($target){ $this->redirect($this->editLink($target)); }
            $this->id_user = 0;
            $this->data['user_link'] = $this->correctionLink(1).'login';
            $this->data['user_link_text'] = 'Login';
        }
    }
    
    public function redirect($link) {
        header("Location: /$link");
        header("Connection: close");
        exit;
    }
}
