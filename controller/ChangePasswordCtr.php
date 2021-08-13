<?php

/**
 * Description of ChangePasswordCtr
 *
 * @author Petr
 */
class ChangePasswordCtr extends Ctr{
    
    public function process($data) {
        $this->data = $data;
        $this->isLogged();
        $this->data['title'] = "Online task - change password";
        $this->data['desc'] = "zatím nic";
        $form_att = array("style" => "bootstrap", "layout" => "");
        $form = new UserAssemblyForm($form_att);
        $control_form = new ControlFormMaster();
        $form->userChangePasswordForm();
        if($_POST) {
            $form->dataPostToFormData(); 
            if($control_form->changePassword($form->getFormData())) {
                $this->redirect('online-task/profile');
            }
            else { $form->setMsgs($control_form->getErrMsg()); }
        }
        $this->data['form'] = $form->create($form->getFormData());
        $view = new ChangePasswordWs();
        $view->show($this->data);
    }    
}
