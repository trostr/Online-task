<?php

/**
 * Description of ControlFormMaster
 *
 * @author Petr
 */
class ControlFormMaster extends ControlForm{
    
    protected $user;
    //protected $ret = true;
    
    public function __construct() {
        $this->user = new User();
    }
    
    public function controlRegistration($data) {
        $ok = true;
        if($this->controlFormData($data)) {
            if(!$this->user->registrationUser($data['name']['value'], $data['mail']['value'], $data['pass']['value'])) {
                $ok = false;
                $this->setFormErrMsg();
            }
        }
        else { $ok = false; }
        return $ok;
    }
    
    public function controlLogin($data) {
        $ok = true;
        if($this->controlFormData($data, false)) {
            if(!$this->user->loginUser($data['name']['value'], $data['pass']['value'])) {
                $ok = false;
                $this->setFormErrMsg();
            }
        }
        else { $ok = false; }
        return $ok;
    }
    
    public function changePassword($data) {
        if($this->controlFormData($data)) {
            if(!$this->user->changePassword($data['old_pass']['value'], $data['pass']['value'])) {
                $this->setFormErrMsg();
                return false;
            }
            return true;
        }
        else { return false; }
    }
    
    protected function setFormErrMsg() {
        $err_msg = $this->user->getErrMsg();
        foreach ($err_msg as $key => $value) {
            $this->err_msg[$key] = $value;
        }
    }
    
}
