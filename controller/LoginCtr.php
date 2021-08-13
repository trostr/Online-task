<?php

/**
 * Description of LoginCtr
 *
 * @author Petr
 */
class LoginCtr extends Ctr{
    
    public function process($data) {
        $this->data = $data;
        $this->isLogged();
        $this->data['title'] = "Online task - login";
        $this->data['desc'] = "zatím nic";
        $form_att = array("style" => "bootstrap", "layout" => "");
        $form = new UserAssemblyForm($form_att);
        $control_form = new ControlFormMaster();
        $form->userLoginForm();
        if($_POST) {
            $form->dataPostToFormData(); 
            if($control_form->controlLogin($form->getFormData(), true)) {
                $this->redirect('online-task/task');
            }
            else { $form->setMsgs($control_form->getErrMsg()); }
        }
        $this->data['form'] = $form->create($form->getFormData());
        $this->data['registr_link'] = '<br><a href="'.$this->editLink('registration').'">New registration</a>';
        $view = new LoginWs();
        $view->show($this->data);
    }
    
}
