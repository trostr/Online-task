<?php

class UserAssemblyForm extends CreateForm{
    
    /**
     * Přednastavený přihlašovací formulář
     */
    public function userLoginForm() {
        $this->setForm('form-login', 'POST');
        $this->addUserNameInput('Name');
        $this->addPasswordInput('Password');
        $this->addButton('Login');
    }
    
    /**
     * Přednastavený registrační formulář
     */
    public function userRegistrationForm() {
        $this->setForm('form-reg', 'POST');
        $this->addUserNameInput('Name');
        //$this->addUserLastNameInput('Last name');
        $this->addMailInput('Mail');
        $this->addPasswordInput('Password');
        $this->addCheckPasswordInput('Check password');
        $this->addButton('Registration');
    }
    
    public function userChangePasswordForm() {
        $this->setForm('form-change-pass', 'POST');
        $this->addOldPasswordInput('Old password');
        $this->addPasswordInput('New password');
        $this->addCheckPasswordInput('Check password');
        $this->addButton('Change password');
    }
    
    
    
    public function addTaskForm() {
        $this->setForm('form-add', 'POST');
        $this->addInput('Name', 'name', 'text', '');
        $this->addTextarea('Text', 'text', 10, '');
        $this->addInput('Deadline', 'deadline', 'date', '');
        //$this->addInput('Status', 'status', 'text', '');
        $this->addButton('Save');
    }
    
    public function addSubTaskForm() {
        $this->setForm('form-add', 'POST');
        $this->addInput('Name', 'name', 'text', '');
        $this->addTextarea('Text', 'text', 10, '');
        //$this->addInput('Deadline', 'deadline', 'date', '');
        //$this->addInput('Status', 'status', 'text', '');
        $this->addButton('Save');
    }
    
    public function editTaskForm($param) {
        $this->setForm('form-edit', 'POST');
        $this->addInput('Name', 'name', 'text', '');
        $this->addTextarea('Text', 'text', 10, '');
        if($param['deadline'] == 0){
            $this->addInput('Deadline', 'deadline', 'date', '');
        }
        if($param['status'] == 0){
            $this->addCheckbox('Splněno', 'status', 1);
        }    
        $this->addButton('Save');
    }
    
    public function dealerForm() {
        $this->setForm('form-dealer', 'POST');
        $this->addInput('Name', 'name', 'text', '');
        $this->addInput('Link', 'link', 'text', '');
        $this->addInput('Category', 'category', 'text', '');
        $this->addInput('Status', 'status', 'text', ''); //pattern
        $this->addInput('Pattern', 'pattern', 'text', '');
        $this->addButton('Save');
    }
    
    public function productForm() {
        $this->setForm('form-product', 'POST');
        $this->addInput('Name', 'name', 'text', '');
        $this->addInput('Link', 'link', 'text', '');
        //$this->addInput('Img link', 'img_link', 'text', '');
        $this->addInput('Category', 'category', 'text', '');
        $this->addTextarea('Short description', 'short_desc', 2, '');
        $this->addTextarea('Description', 'p_desc', 5, '');
        $this->addTextarea('Specification', 'p_spec', 5, '');
        $this->addInput('Price', 'price', 'text', '');
        $this->addInput('Status', 'status', 'text', '');
        $this->addInput('Compare', 'compare', 'text', '');
        $this->addButton('Save');
    }
    
    public function categoryForm() {
        $this->setForm('form-category', 'POST');
        $this->addInput('Name', 'name', 'text', '');
        $this->addInput('Link', 'link', 'text', '');
        $this->addInput('Parent', 'parent', 'text', '');
        $this->addInput('Order', 'c_order', 'text', '');
        $this->addInput('Status', 'status', 'text', '');
        $this->addButton('Save');
    }
    
    public function imageForm() {
        $this->setForm('form-image', 'POST');
        $this->addInput('Name', 'name', 'text', '');
        $this->addInput('Link', 'link', 'text', '');
        $this->addInput('Product', 'id_product', 'text', '');
        $this->addInput('Order', 'img_order', 'text', '');
        $this->addInput('Status', 'status', 'text', '');
        $this->addButton('Save');
    }
    
    public function imageAddForm() {
        $this->setForm('form-add-image', 'POST', 'multipart/form-data');
        $this->addFile('Obrázek produktu', 'image');
        $this->addButton('Add image');
    }
    
    public function contactForm() {
        $this->setForm('form-contact', 'POST');
        $this->addUserNameInput('Jméno');
        $this->addMailInput('Email');
        $this->addTextarea('Vzkaz - dotaz', 'text', 3, '');
        $this->addButton('Odeslat');
    }
    
    /**
     * Přednastavený Input element formuláře
     * @param string $label Popis políčka
     */
    public function addUserNameInput($label) {
        $this->addInput($label, 'name', 'text', '');
        $this->setFocus();
        $this->setRequired();
        $this->setRule('smin', 3, "Příliš krátký text.");
        $this->setRule('smax', 40, "Příliš dlouhý text.");
        $this->setRule('banchars', true, "Obsahuje nepovolené znaky.");
    }
    
    /**
     * Přednastavený Input element formuláře
     * @param string $label Popis políčka
     */
    public function addUserLastNameInput($label) {
        $this->addInput($label, 'last_name', 'text', '');
        $this->setRequired();
        $this->setRule('smin', 3, "Příliš krátký text.");
        $this->setRule('smax', 40, "Příliš dlouhý text.");
        $this->setRule('banchars', true, "Obsahuje nepovolené znaky.");
    }
    
    
    /**
     * Přednastavený Input element formuláře
     * @param string $label Popis políčka
     */
    public function addMailInput($label) {
        $this->addInput($label, 'mail', 'mail', '');
        $this->setRequired();
        $this->setRule('smax', 40, "Příliš dlouhý text.");
        $this->setRule('mail', true, "Neplatný formát emailu.");
    }
    
    /**
     * Přednastavený Input element formuláře
     * @param string $label Popis políčka
     */
    public function addPasswordInput($label) {
        $this->addInput($label, 'pass', 'password', '');
        $this->setRequired();
        $this->setRule('smin', 3, "Příliš krátké heslo.");
        $this->setRule('smax', 40, "Příliš dlouhé heslo.");
        $this->setRule('banchars', true, "Obsahuje nepovolené znaky.");
    }
    
    /**
     * Přednastavený Input element formuláře
     * @param string $label Popis políčka
     */
    public function addCheckPasswordInput($label) {
        $this->addInput($label, 'xpass', 'password', '');
        $this->setRequired();
        $this->setRule('samepass', 'pass', "Nestejná hesla.");
    }
    
    /**
     * Přednastavený Input element formuláře
     * @param string $label Popis políčka
     */
    public function addOldPasswordInput($label) {
        $this->addInput($label, 'old_pass', 'password', '');
        $this->setRequired();
        $this->setRule('smin', 3, "Příliš krátké heslo.");
        $this->setRule('smax', 40, "Příliš dlouhé heslo.");
        $this->setRule('banchars', true, "Obsahuje nepovolené znaky.");
    }
    
    public function addCheckboxInput($label) {
        $this->addCheckbox($label, 'status');
    }
    
    
}
