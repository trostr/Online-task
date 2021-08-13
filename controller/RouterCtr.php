<?php

/**
 * Description of smerovac
 *
 * @author Petr
 */
class RouterCtr extends Ctr{
    
    protected function editLink($link) {
        //echo $_SERVER['SERVER_NAME'].'<br>';
        //echo $_SERVER['PATH'].'<br>';
        //var_dump($link);
        //echo '<br>';
        $edited_link = parse_url($link);
        //var_dump($edited_link);
        //echo '<br>';
        $edited_link["path"] = substr($edited_link["path"], strlen($this->folder));
        //var_dump($edited_link["path"]);
        //echo '<br>';
        $edited_link["path"] = ltrim($edited_link["path"],"/");
        $edited_link["path"] = trim($edited_link["path"]);
        return explode('/', $edited_link["path"]);
    }
    
    protected function toRedirect($link) {
        $new_link = $this->folder.$link;
        $this->redirect($new_link);
    }
    
    /*
    protected function className($text) {
	$str = str_replace('-', ' ', $text);
	$str = ucwords($str);
	$str = str_replace(' ', '', $str);
	return $str;
    }
     * 
     */
    
    
    
    public function process($link) {
        // ---------- save user access
        //$acs = new Access();
        //$acs->addAccess();
        // ---------- process
        //var_dump($link);
        //echo '<br>';
        $edited_link = $this->editLink($link);
        //var_dump($edited_link);
        
        if(empty($edited_link[1]) || $edited_link[1] == "index.php"){
            //$this->toRedirect($this->base_link);
            //$edited_link[0] = "zvadlo";
            $class_name = $this->className("task")."Ctr";
            $this->controller = new $class_name;
        }
        else { 
            $class_name = $this->className($edited_link[1])."Ctr";
            if (file_exists('controller/' . $class_name . '.php')){
		
                $this->controller = new $class_name;
            }
            else { $this->toRedirect($this->base_link); }
                //$edited_link[0] = "zvadlo";
                //$class_name = $this->className($edited_link[0])."Ctr";
            //$this->controller = new $class_name;
            
            /*
            // ----------- navratovy parametr pro prihlaseni a registraci
            $this->setPreviousLink($edited_link);
            $data['parametr'] = $edited_link;    
            // ---------- predani dat konkretnimu kontroleru
            $this->controller->process($data);
             * 
             */
        
        }
        // ----------- navratovy parametr pro prihlaseni a registraci
        //$this->setPreviousLink($edited_link);
           
        // ---------- predani dat konkretnimu kontroleru
         
        $data['parametr'] = $edited_link;
        $this->controller->process($data);
         
    }
    
    public function setPreviousLink($link) {
        if($link[0] != "prihlaseni" && $link[0] != "registrace") {
            $_SESSION['previous_website'] = $link[0];
        }
    }
    
    
}
