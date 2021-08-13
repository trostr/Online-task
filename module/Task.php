<?php

/**
 * Description of Task
 *
 * @author Petr
 */
class Task {
    
    protected $data;
    protected $path;
    protected $parents_line;
    protected $add_link;

    public function __construct() {
        $this->createDbTable();
        //$this->setAttributs();
    }
    
    public function setPath($path) {
        $this->path = $path;
    }
    
    public function correctionLink($pos) {
        $str = '';
        $count_folder = count($this->path)-1;
        if($count_folder>$pos){
            $count_folder = $count_folder - $pos;
            for($i=0; $i<$count_folder;$i++){
                $str .= '../';
            }
        }
        return $str;
    }
    
    public function getList($user, $task) {
        $data = $this->getData($user, $task);
        if($data){ return $this->buildList($data); }
        else { return array('<p>No task</p>'); }
    }
    
    public function buildList($data) {
        foreach ($data as $key => $value) {
            $alert_type = $this->alertType($data[$key]);
            $sub_task = $this->countSubTask($data[$key]['id_task']);
            $sub_link = $this->correctionLink(1).'task/'.$data[$key]['id_task'];
            $edit_link = $this->correctionLink(1).'task/edit/'. $data[$key]['id_task'];
            $html_arr[] = '<div class="alert '.$alert_type.'">';
            $html_arr[] = '<div class="alert-head">';
            $html_arr[] = '<strong>'. htmlspecialchars($data[$key]['name']).'</strong>';
            $html_arr[] = '<div class="info-head">';
            if($data[$key]['status'] == 0){
                $deadline = $this->deadlineString($data[$key]['deadline']);
                $html_arr[] = '<strong><span class="glyphicon glyphicon-time"></span>'. $deadline .'</strong>';
            }
            $html_arr[] = '<a href="'.$sub_link.'" class="alert-link"><span class="glyphicon glyphicon-chevron-down"></span><span class="no-decor"> '.$sub_task.'</span></a>';
            $html_arr[] = '<a href="'.$edit_link.'" class="alert-link"><span class="glyphicon glyphicon-edit"></span></a>';
            $html_arr[] = '</div>';
            $html_arr[] = '</div>';
            $html_arr[] = '<p>'.htmlspecialchars($data[$key]['text']).'</p>';
            $html_arr[] = '</div>';
        }
        return $html_arr;
    }
    
    public function alertType($data) {
        if($data['status'] == 1){ return $str = "alert-success"; }
        if($data['status'] == 0){
            $diff_date = $this->diffDate($data['deadline']);
            if($diff_date < 8){ return $str = "alert-danger"; }
            else { return $str = "alert-info"; }
        }
    }
    
    public function deadlineString($date) {
        $diff_date = $this->diffDate($date);
        if($diff_date < 0){ return $str = " it's late"; }
        elseif( $diff_date == 0){ return $str = " today"; }
        elseif($diff_date == 1){ return $str = ' tomorrow'; }
        else { return $str = ' '.$diff_date. ' days'; }
    }
    
    public function diffDate($date) {
        $date_now = new DateTime();
        $date_task = new DateTime($date);
        $interval = date_diff(new DateTime($date_now->format('Y-m-d')), $date_task);
        if($date_now > $date_task){
            return $interval->format('%a') * -1 ;
        }
        else { return $interval->format('%a'); }
    }
    
    public function addLink($task) {
        if($task == 0){ $str = '<a href="'.$this->correctionLink(2).'task/add">Add task</a>'; }
        else { $str = '<a href="'. $this->correctionLink(2).'add/'.$task.'">Add task</a>'; }
        return $str;
    }
    
    public function parentsLineLink($task) {
        $str = '';
        $arrow = '';
        $name = '';
        $link = '';
        $w = true;
        while($w){
            if($task == 0){
                $name = 'Root';
                $link = $this->correctionLink(1).'task';
                //$link = $this->correctionLink();
                $w = false;
                $arrow = '';
            }
            else{
                $row = $this->getRow($task);
                $name = $row['name'];
                $link = $this->correctionLink(2) . $task;
                $task = $row['parent'];
                $arrow = ' -> ';
            }
            $str = $arrow.'<a href="'.$link.'">'.$name.'</a>' . $str;
        }
        return $str;
    }
    
    public function changeStatusParent($parent) {
        if($parent > 0){
            $parent_status = $this->getStatus($parent);
            $sub_task_status = $this->minStatus($parent);
            if($parent_status != $sub_task_status){
                $this->updateTask(array("status" => $sub_task_status), $parent);
                $parent = $this->parentNumber($parent);
                $this->changeStatusParent($parent);
            }
        }
    }
    
    public function changeDeadlineSubTask($id_task, $deadline) {
        $sub_task = $this->getSubTaskList($id_task);
        if($sub_task){
            foreach ($sub_task as $key => $value) {
                $this->updateTask(array("deadline" => $deadline), $sub_task[$key]['id_task']);
                $this->changeDeadlineSubTask($sub_task[$key]['id_task'], $deadline);
            }
        }
    }
    /*
    public function checkSamplDeadline() {
        $id_sampl_task = 1;
        $diff_days = 15;
        $sampl_deadline = $this->getDeadline($id_sampl_task);
        $diff = $this->diffDate($sampl_deadline);
        if($diff < $diff_days){
            
        }       
    }
    
    public function changeDeadlineSamplTask() {
        $sampl_task = array(1=>15, 2=>5);
    }
     * 
     */
    
    
    
    public function minStatus($parent) {
        return Db::querySingle('SELECT MIN(status) FROM task WHERE parent = ?', $parent);
    }
    
    public function getStatus($task) {
        return Db::querySingle('SELECT status FROM task WHERE id_task = ?', $task);
    }
    
    public function getDeadline($task) {
        return Db::querySingle('SELECT deadline FROM task WHERE id_task = ?', $task);
    }
    
    public function updateTask($data, $id_task) {
        Db::update('task', $data, 'WHERE id_task = '.$id_task);
    }
    
    public function addNewTask($data) {
        Db::insert('task', $data);
    }
    
    public function parentNumber($id_task) {
        return Db::querySingle('SELECT parent FROM task WHERE id_task = ?', $id_task);
    }
    
    public function getSubTaskList($parent) {
        return Db::queryAll('SELECT id_task FROM task WHERE parent = ?', $parent);
    }
    
    /**
     * Vrátí počet potomků
     * @param type $parent
     * @return type
     */
    public function countSubTask($parent) {
        return Db::querySingle('SELECT COUNT(id_task) FROM task WHERE parent = ?', $parent);
    }
    
    public function isPossible($task, $user) {
        return Db::querySingle('SELECT id_task FROM task WHERE id_task = ? AND id_user = ?', $task, $user);
    }
    
    protected function getData($user, $task) {
        return Db::queryAll('SELECT * FROM task WHERE id_user = ? AND parent = ? ORDER BY status, deadline', $user, $task);
    }
    
    public function getRow($task) {
        return Db::queryOne('SELECT * FROM task WHERE id_task = ?', $task);
    }
    
    public function removeTask($task) {
        return Db::query('DELETE FROM task WHERE id_task = ?', $task);
    }
    
    public function createDbTable() {
       return Db::query('CREATE TABLE IF NOT EXISTS task(
                id_task int(11) AUTO_INCREMENT,
                id_user int(11),
                name varchar(255),
                text varchar(255),
                parent int(11),
                deadline date,
                status int(11),
                PRIMARY KEY (id_task)) 
                CHARACTER SET utf8 
                COLLATE utf8_czech_ci'); 
    }
}
