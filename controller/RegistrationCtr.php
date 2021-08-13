<?php

/**
 * Description of RegistrationCtr
 *
 * @author Petr
 */
class RegistrationCtr extends Ctr{
    
    
    
    public function process($data) {
        $this->data = $data;
        $this->isLogged();
        $this->data['title'] = "Online task - registrace";
        $this->data['desc'] = "Registrace nového uživatele.";
        $form_att = array("style" => "bootstrap", "layout" => "");
        $form = new UserAssemblyForm($form_att);
        $control_form = new ControlFormMaster();
        $form->userRegistrationForm();
        if($_POST) {
            // přepsání dat z POST do formuláře
            $form->dataPostToFormData(); 
            // 
            if($control_form->controlRegistration($form->getFormData())) {
                //$this->redirect($this->folder.$data['parametr'][0]);
                // přesměrování do uživatelského profilu
                $this->redirect('online-task/profil');
            }
            // předání chybových msg do formuláře
            else { $form->setMsgs($control_form->getErrMsg()); }
        }
        // sestavení celého formuláře
        $this->data['form'] = $form->create($form->getFormData());
        $view = new RegistrationWs();
        // výpis formuláře
        $view->show($this->data);    
    }
}
