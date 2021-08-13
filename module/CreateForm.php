<?php

/**
 * Description of CreateFormBs
 *
 * @author Petr
 */
class CreateForm extends Form{
    
    protected $style;
    //protected $layout;
    protected $class_form;


    public function __construct($param) {
        $this->setAttributs($param);    
    }
    
    public function setAttributs($param) {
        $this->style = $param['style'];
        $this->setClassForm($param['layout']);
    }
    
    public function setClassForm($layout) {
        if($layout){
            $this->class_form = ' class="'.$layout.'"';
        }
        else{ $this->class_form = ""; }
    }
    
    /**
     * Vrátí pole s celým formulářem v HTML řádcích
     * @param array $data
     * @return array
     */
    public function create($data) {
        foreach ($data as $d => $val){
            if($d == 'form') { $arr[] = $this->buildFirstLine($val); }
            elseif($d == 'button') { $arr[] = $this->buildButton($val); }
            else { $arr[] = $this->switchType($val); }
        }
        $arr[] = $this->buildLastLine();
        return $arr;
    }
    
    public function switchType($data) {
        switch ($data['type']) {
            case 'textarea':
                return $this->buildTextarea($data);
            case 'checkbox':
                return $this->buildCheckbox($data);
            case 'file':
                return $this->buildFile($data);
            default:
                return $this->buildInput($data);
        }
    }
    
    /**
     * Vrátí atributy ve string formátu
     * @param array $data Pole atributů Input elementu
     * @return string 
     */
    protected function getAttribut($data) {
        $str = "";
        if(isset($data['attr'])){
            foreach ($data['attr'] as $key => $value) {
                $str .= $this->attributToString($key,$value);
            }
        }
        return $str;
    }
    
    /**
     * Vrací attribut ve string větě.
     * @param string $key
     * @param mixed $value
     * @return string
     */
    protected function attributToString($key, $value) {
        if(is_bool($value)) { return $str = ' '.$key; }
        else { return $str = ' '.$key.'="'.$value.'"'; }
    }
     
    /**
     * Vrátí první řádek formuláře v HTML
     * @param array $data
     * @return string
     */
    protected function buildFirstLine($data) {
        $str = '<form';
        $str .= $this->class_form;
        $str .= ' method="'.$data['method'].'"';
        if(isset($data['enctype']) && $data['enctype']){
            $str .= 'enctype="'.$data['enctype'].'" ';
        }
        $str .= '>';
        return $str;
    }
    
    /**
     * Vrátí poslední řádek formuláře v HTML
     * @return string
     */
    protected function buildLastLine() {
        return $str = '</form>';
    }
    
    public function labelForm($data) {
        if($this->style == "bootstrap"){
            return $str = '<label for="'.$data['name'].'">'.$data['label'].':</label>';
        }
        if($this->style == "classic"){
            return $str = '<span class="form-label">'.$data['label'].'</span><br>';
        }
    }
    
    /**
     * Vrátí řádek Input elemnetu formuláře v HTML
     * @param array $data
     * @return string
     */
    protected function buildInput($data) {
        $str = '<div class="form-group">';
        $str .= $this->labelForm($data);
        $str .= '<input';
        if($this->style == "bootstrap"){
            $str .= ' class="form-control"';
        }
        $str .= ' type="'.$data['type'].'"';
        $str .= ' name="'.$data['name'].'"';
        $str .= ' id="'.$data['name'].'"';
        if($data['type'] == 'password'){ // clear password value
            $str .= ' value=""';
        }
        else { 
            $ht = htmlentities($data['value']); //htmlspecialchars
            $str .= ' value="'.$ht.'"';
        }
        $str .= $this->getAttribut($data).' >';
        if($this->style == "classic"){ $str .= "<br>"; }
        if($data['msg']) {
            $str .= '<span class="help-block">'.$data['msg'].'</span>';
        }
        //$str .= '<br>';
        $str .= '</div>';
        return $str;
    }
    
    protected function buildTextarea($data) {
        $str = '<div class="form-group">';
        $str .= $this->labelForm($data);
        $str .= '<textarea';
        if($this->style == "bootstrap"){
            $str .= ' class="form-control"';
        }
        $str .= ' rows="'.$data['rows'].'"';
        $str .= ' name="'.$data['name'].'"';
        $str .= ' id="'.$data['name'].'"';
        $str .= $this->getAttribut($data).' >';
        $ht = htmlentities($data['value']);
        $str .= $ht;
        $str .= '</textarea>';
        if($this->style == "classic"){ $str .= "<br>"; }
        if($data['msg']) {
            $str .= '<span class="help-block">'.$data['msg'].'</span>';
        }
        $str .= '</div>';
        return $str;
    }
    
    protected function buildSelect($data) {
        $str = '<span class="form-label">'.$data['label'].'</span>';
        $str .= '<select ';
        //$str .= 'type="'.$data['type'].'" ';
        $str .= 'name="'.$data['name'].'" ';
        if($data['type'] == 'password'){ // clear password value
            $str .= 'value="" ';
        }
        else { 
            $ht = htmlentities($data['value']);
            $str .= 'value="'.$ht.'" ';
        }
        $str .= $this->getAttribut($data).' ><br>';
        if($data['msg']) {
            $str .= '<span class="form-err-msg">'.$data['msg'].'</span><br>';
        }
        $str .= '<br>';
        return $str;
    }
    
    public function buildFile($data) {
        $str = '<span class="form-label">'.$data['label'].'</span>';
        $str .= '<input ';
        $str .= 'type="'.$data['type'].'" ';
        $str .= 'name="'.$data['name'].'" ';
        $str .= $this->getAttribut($data).' ><br>';
        if($data['msg']) {
            $str .= '<span class="form-err-msg">'.$data['msg'].'</span><br>';
        }
        $str .= '<br>';
        return $str;
    }
    
    protected function buildHidden($data) {
        $str .= '<input ';
        $str .= 'type="hidden" ';
        $str .= 'name="'.$data['name'].'" ';
        $str .= 'value="0">';
        return $str;
    }
    
    protected function buildCheckbox($data) {
        if($this->style == "bootstrap"){
            $str = '<div class="checkbox">';
            $str .= '<label><input type="checkbox" name="'.$data['name'].'"';
            $str .= 'value="1" ';
            if($data['value'] == 1){
                $str .= ' checked'; 
            }
            $str .= $this->getAttribut($data);
            $str .= '> '.$data['label'].'</label></div>';
            return $str;
        }
        if($this->style == "classic"){
            $str = '<input ';
            $str .= 'type="'.$data['type'].'" ';
            $str .= 'name="'.$data['name'].'" ';
            $str .= 'value="1" ';
            if($data['value'] == 1){
               $str .= 'checked'; 
            }
            $str .= $this->getAttribut($data).' > '.$data['label'].'<br>';
            $str .= '<br>';
            return $str;
        }
    }
    
    /**
     * Vrátí řádek Button elementu formuláře v HTML
     * @param array $data
     * @return string
     */
    protected function buildButton($data) {
        if($this->style == "bootstrap"){
            return $str = '<button type="submit" class="btn btn-default">'.$data['val'].'</button>';
        }
        if($this->style == "classic"){
            return $str = '<input type="'.$data['type'].'" value="'.$data['val'].'">';
        }
    }
}
