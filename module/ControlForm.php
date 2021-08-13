<?php

/**
 * Description of controlForm
 *
 * @author Petr
 */
class ControlForm {
    
    protected $ok = true;
    protected $mem;
    protected $err_msg;
    protected $illegal_chars = array(" ","!","@","#","$","%","^","&","*","(",")","=","+","[","]","{","}","`","~","|","\"","?","/",";",":","\\","\n","\r\n","'","<",">",",","\r");

    public function controlFormData($data) {
        $this->mem = $data;
        foreach ($data as $arr) {
            if(array_key_exists('rule', $arr)) {
                $this->controlRules($arr);
            }
        }
        return $this->ok;
    }
    
    public function getErrMsg(){
        return $this->err_msg;
    }
    
    /**
     * Vyhledává a provádí podmínky z pole $data
     * @param array $data Data Input elementu
     */
    protected function controlRules($data) {
        $this->err_msg[$data['name']] = '';
        foreach (array_keys($data['rule']) as $key) {
            $name_function = $key.'Rule';
            if(method_exists($this, $name_function)){  
                $this->$name_function($data);
            }
        }
    }
    
    protected function requiredRule($data) {
        if(empty($data['value'])) {
            $this->err_msg[$data['name']] = $data['rule']['required']['msg'];
            $this->ok = false;
        }
    }
    
    protected function sminRule($data) {
        if(!empty($data['value']) && mb_strlen($data['value']) < $data['rule']['smin']['value']) {
            $this->err_msg[$data['name']] = $data['rule']['smin']['msg'];
            $this->ok = false;
        }
    }
    
    protected function smaxRule($data) {
        if(!empty($data['value']) && mb_strlen($data['value']) > $data['rule']['smax']['value']) {
            $this->err_msg[$data['name']] = $data['rule']['smax']['msg'];
            $this->ok = false;
        }
    }
    
    protected function bancharsRule($data) {
        if(!empty($data['value']) && $this->checkIllegalChars($data['value'])){
            $this->err_msg[$data['name']] = $data['rule']['banchars']['msg'];
            $this->ok = false;
        }
    }
    
    protected function mailRule($data) {
        if(!empty($data['value']) && !preg_match('/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', $data['value'])) {
            $this->err_msg[$data['name']] = $data['rule']['mail']['msg'];
            $this->ok = false;
        }
    }
    
    protected function samepassRule($data) {
        if(!empty($data['value']) && $this->mem[$data['rule']['samepass']['value']]['value'] !== $data['value']){
            $this->err_msg[$data['name']] = $data['rule']['samepass']['msg'];
            $this->ok = false;
        }    
    }
    
    
    protected function checkIllegalChars($text) {
        $ok = false;
        foreach ($this->illegal_chars as $val) {
            if(mb_strpos($text, $val) !== false) {
                $ok =  true;
                break;
            }
        }
        return $ok;
    }
    
}
