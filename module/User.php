<?php

/**
 * Description of User
 *
 * @author Petr
 */
class User {
    
    public $err_msg;


    public function __construct() {
        $this->createDbTable();
    }
    
    public function loginUser($name,$password) {
        $ok = true;
        $this->err_msg['name'] = '';
        $this->err_msg['pass'] = '';
        $user = $this->getRow('user', 'name', $name);
        if($user) {
            if(hash('sha512', $password) == $user['password']) {
                $this->userToSess($user['id_user'], $user['name'], $user['mail']); 
            }
            else {
                $ok = false;
                $this->err_msg['pass'] = 'Neplatné heslo.';
            }    
        }
        else {
            $ok = false;
            $this->err_msg['name'] = 'Neplatné přihlašovací jméno.';
        } 
        return $ok;
    }
    
    public function registrationUser($name, $mail, $password) {
        $ok = true;
        $this->err_msg['name'] = '';
        if($this->getRow('user', 'name', $name)) {
            $this->err_msg['name'] = "Tohle jméno už někdo používá.";
            $ok = false;
        }
        $this->err_msg['mail'] = '';
        if($this->getRow('user', 'mail', $mail)) {
            $this->err_msg['mail'] = "Tento email už někdo používá.";
            $ok = false;
        }
        if($ok) {
            //$time = new DateTime();
            Db::query('
                    INSERT INTO user (name, mail, password, status)
                    VALUES (?, ?, ?, ?)
                    ', $name,  $mail, hash('sha512', $password), 0);
            $this->user_id = Db::getLastId();
            $this->userToSess($this->user_id, $name, $mail);
            //$this->completeRegistration();
            if($_SERVER['SERVER_NAME'] !== 'localhost'){
                $this->sendRegistrationMail($mail);
            }
        }
        return $ok;
    }
    
    public function changePassword($old, $new) {
        $this->err_msg['old_pass'] = '';
        $user = $this->getRow('user', 'id_user', $_SESSION['id']);
        if($user) {
            if(hash('sha512', $old) == $user['password']) {
                $this->updateUser(array("password" => hash('sha512', $new)), $_SESSION['id']);
                return true;
            }
            else {
                $this->err_msg['old_pass'] = 'Neplatné heslo.';
                return false;
            }   
        }
        else{
            $this->err_msg['old_pass'] = 'Uživatel nenalezen.';
            return false;
        }
    }
    
    public function sendRegistrationMail($to) {
        $mail = new Email();
        $mail->SendEmail('online-task@trostr.cz', $to, 'Nová registrace', 'Byla provedena nová registrace.');
        $mail->SendEmail('online-task@trostr.cz', 'mo.petr.sauer@gmail.com', 'Nový uživatel', 'Byla provedena nová registrace.');
    }
    
    protected function userToSess($id, $name, $mail) {
        $_SESSION['id'] = $id;
        $_SESSION['name'] = $name;
        //$_SESSION['level'] = $level;
        $_SESSION['mail'] = $mail;
    }
    
    public function isLogged() {
        if(isset($_SESSION['id']) && $_SESSION['id']){
            return $_SESSION['id'];
        }
        else{ return 0; }
    }
    
    public function getErrMsg() {
        return $this->err_msg;
    }
    
    protected function getRow($table, $column, $value) {
        return DB::queryOne('SELECT * FROM '.$table.' WHERE '.$column.' = ?', $value);
    }
    
    public function updateUser($data, $id_user) {
        Db::update('user', $data, 'WHERE id_user = '.$id_user);
    }
    
    public function createDbTable() {
       return Db::query('CREATE TABLE IF NOT EXISTS user(
                id_user int(11) AUTO_INCREMENT,
                id_code int(11),
                name varchar(255),
                mail varchar(255),
                password varchar(255),
                status int(11),
                PRIMARY KEY (id_user)) 
                CHARACTER SET utf8 
                COLLATE utf8_czech_ci'); 
    }
}
