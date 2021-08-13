<?php

/**
 * Description of zakladWs
 *
 * @author Petr
 */
class BaseWs {
    protected $folder;
    protected $user = "";
    protected $user_lvl = false;

    public function __construct() {
    
        $local = new Location();
        $this->folder = "/".$local->getFolder();
    }
    
    protected function editLink($link) {
        return $str = 'online-task/'. $link;
    }
    
    protected function header($data) {
        ?>
        <!DOCTYPE html>
            <html
                <head>
                    <title><?= $data['title'] ?></title>
                    <meta name="description" content="<?= $data['desc'] ?>">
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link rel="icon" href="https://<?= $_SERVER['SERVER_NAME'] ?>/online-task/check.png" type="image/x-icon">
                    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                    <link rel="stylesheet" type="text/css" href="https://<?= $_SERVER['SERVER_NAME'] ?>/online-task/task_style.css">
                </head>
                <body>
            <?php
    }
    protected function body($data) {
        ?>
        
        <div class="container">
        <h1>Task - online</h1>
        
        </div>
        <?php        
    }
    
    protected function heel($data) {
        ?>
        <footer>
            <div id="user">
            <a href="<?= $data['user_link'] ?>"><?= $data['user_link_text'] ?></a> <!-- <span class="glyphicon glyphicon-log-in"></span> -->  
            </div>
            <div id="author">
                <a href="https://trostr.cz">&copy 2018 - Petr Šauer</a>
            </div>
        </footer>   
        </body>
        </html>
        <?php
    }
    
    public function show($data) {
        $this->header($data);
        $this->body($data);
        $this->heel($data);
    }
    
    public function showArray($data) {
        foreach ($data as $d) {
                echo($d."\n");
        }
    }
    
}
