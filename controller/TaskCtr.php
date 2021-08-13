<?php

/**
 * Description of TaskCtr
 *
 * @author Petr
 */
class TaskCtr extends Ctr{
    
    protected $user;
    protected $task;
    protected $form;
    protected $id_task;


    public function process($data) {
        $this->task = new Task();
        $this->user = new User();
        $this->task->setPath($data['parametr']);
        $this->data = $data;
        $this->isLogged();
        $this->pathSwitch();
    }
    
    public function pathSwitch() {
        $path = $this->data['parametr'];
        if(isset($path[2])){
            switch ($path[2]) {
                case  "add":
                    $this->add($path);
                    //var_dump($path);
                    break;
                case  "edit":
                    $this->edit($path);
                    //var_dump($path);
                    break;
                case "remove":
                    $this->remove($path);
                    //var_dump($path);
                    break;
                case is_numeric($path[2]):
                    $this->taskList($path[2]);
                    //var_dump($path);
                    break;
                default:
                    $this->redirect('online-task/task');
                    //var_dump($path);
                    break;
            }
        }
        else{ $this->taskList(); }
    }
    
    /**
     * Ochrana před neočekávaným parametrem URL, 
     * přesměruje na $target
     * @param type $number 
     * @param type $target
     */
    public function unexpectedPath($number, $target) {
        if(isset($this->data['parametr'][$number])){
            $this->redirect($target);
        }
    }
    
    public function belongsToUser($id_task, $target) {
        if($id_task > 0 && $this->task->isPossible($id_task, $this->id_user) == false){
            $this->redirect($target);
        }
    }
    
    public function taskList($id_task = 0) {
        $this->unexpectedPath(3, 'online-task/task/'.$id_task);
        $this->belongsToUser($id_task, 'online-task/task');
        $this->data['task_list'] = $this->task->getList($this->id_user, $id_task);
        $this->data['add_link'] = $this->task->addLink($id_task);
        $this->data['parents_line'] = $this->task->parentsLineLink($id_task);
        $this->data['title'] = "Online task - list";
        $this->data['desc'] = "zatím nic";
        $this->showListView();
    }
    
    public function add($path) {
        $parent = 0;
        $task_link = "";
        $task_number = 0;
        $form_att = array("style" => "bootstrap", "layout" => "");
        $this->form = new UserAssemblyForm($form_att);
        if(isset($path[3]) && is_numeric($path[3])){
            $this->unexpectedPath(4, 'online-task/task/add/'.$path[3]);
            $this->belongsToUser($path[3], 'online-task/task');
            $parent = $path[3];
            $task_link = "/".$path[3];
            $task_number = $path[3];
        }
        if($parent == 0){
            $this->form->addTaskForm();
        }
        else { 
            $this->form->addSubTaskForm(); 
        }
        if($_POST) {
            if($this->id_user > 0){
                $this->form->dataPostToFormData();
                $this->form->formDataToDbData();
                $new_data = $this->form->getDbData();
                $new_data['parent'] = $parent;
                $new_data['status'] = 0;
                $new_data['id_user'] = $this->id_user;
                if($parent>0){
                    $new_data['deadline'] = $this->task->getDeadline($parent);
                }
                $this->task->addNewTask($new_data);
                $this->task->changeStatusParent($parent);
            }
            //var_dump($this->form->getFormData());
            $this->redirect('online-task/task'.$task_link);
        }
        $this->data['form'] = $this->form->create($this->form->getFormData());
        $this->data['parents_line'] = $this->task->parentsLineLink($task_number);
        $this->data['title'] = "Online task - add new";
        $this->data['desc'] = "zatím nic";
        $this->showFormView();        
    }
    
    protected function setEditStatus($new_data, $row, $sub_task) {
        if(!isset($new_data['status'])){
            $new_data['status'] = 0;
            if($sub_task == 1){
                $new_data['status'] = $row['status'];
            }
        }
        return $new_data['status'];
    }
    
    public function edit($path) {
        //$this->unexpectedPath(3, 'task');
        //$parent = 0;
        $sub_task = 0;
        $task_link = "";
        $form_att = array("style" => "bootstrap", "layout" => "");
        $this->form = new UserAssemblyForm($form_att);
        if(isset($path[3]) && is_numeric($path[3]) && $path[3] > 0){
            $this->unexpectedPath(4, 'online-task/task/edit/'.$path[3]);
            $this->belongsToUser($path[3], 'online-task/task');
            $row = $this->task->getRow($path[3]);
            //$parent = $this->task->parentNumber($path[2]);
            $sub_task = $this->task->countSubTask($path[3]);
            if($sub_task > 0){ $sub_task = 1; }
            $this->form->editTaskForm($param = array("deadline" => $row['parent'], "status" => $sub_task));
            $this->form->dataToFormData($row);
            //var_dump($row);
            $task_link = "/".$row['parent'];
            if($_POST) {
                if($this->id_user > 0){
                    $this->form->dataPostToFormData();
                    $new_data = $this->form->getDbData();
                    $new_data['status'] = $this->setEditStatus($new_data, $row, $sub_task);
                    $this->task->updateTask($new_data, $path[3]);
                    $this->task->changeStatusParent($row['parent']);
                    if($row['parent'] == 0){
                        $this->task->changeDeadlineSubTask($path[3], $new_data['deadline']);
                    }
                }
                $this->redirect('online-task/task'.$task_link);
            }
            $this->data['form'] = $this->form->create($this->form->getFormData());
            if($this->task->countSubTask($path[3]) == 0){
                $this->data['remove_link'] = '<br><a href="../remove/'.$path[3].'">Remove this task</a><br>';
            }
            $this->data['parents_line'] = $this->task->parentsLineLink($row['parent']);
            $this->data['title'] = "Online task - edit";
            $this->data['desc'] = "zatím nic";
            $this->showFormView();
        }
        else { $this->redirect('online-task/task'); }
    }
    
    public function remove($path) {
        if(isset($path[3]) && is_numeric($path[3]) && $path[3] > 0){
            $this->unexpectedPath(4, 'online-task/task/remove/'.$path[3]);
            $this->belongsToUser($path[3], 'online-task/task');
            $parent = $this->task->parentNumber($path[3]);
            if($this->id_user > 0){
                $this->task->removeTask($path[3]);
            }    
            $this->redirect('online-task/task/'.$parent);
        }
        else { $this->redirect('online-task/task'); }
    }
    
    private function showListView() { 
        $view = new TaskWs();
        $view->show($this->data);
    }
    
    private function showFormView() { 
        $view = new TaskFormWs();
        $view->show($this->data);
    }
    
}
