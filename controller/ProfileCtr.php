<?php

/**
 * Description of ProfileCtr
 *
 * @author Petr
 */
class ProfileCtr extends Ctr{
    
    public function process($data) {
        $this->data = $data;
        $this->isLogged('login');
        $this->data['title'] = "Online task - user profile";
        $this->data['desc'] = "zatím nic";
        $view = new ProfileWs();
        $view->show($this->data);
    }
}
