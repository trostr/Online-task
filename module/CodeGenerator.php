<?php

/**
 * Description of CodeGenerator
 *
 * @author Petr
 */
class CodeGenerator {
    
    protected $num = "0123456789";
    protected $cap_lett = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    protected $low_lett = "abcdefghijklmnopqrstuvwxyz";
    
    public function randomCode($length) {
        $str = "";
        $char = $this->cap_lett.$this->num.$this->low_lett.$this->num;
        for($i=0; $i<$length; $i++) {
            $pos = rand(0, mb_strlen($char)-1);
            $str .= $char[$pos]; 
        }
        return $str;
    }
    
    public function hashCode($str) {
        return hash('sha1', $str);
    }
    
}
