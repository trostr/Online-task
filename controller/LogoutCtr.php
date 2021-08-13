<?php

class LogoutCtr extends Ctr{
    
    public function process($data) {
        session_destroy();
        $this->redirect('online-task/task');
    }
    
}
