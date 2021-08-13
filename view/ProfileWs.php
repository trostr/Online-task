<?php

/**
 * Description of ProfileWs
 *
 * @author Petr
 */
class ProfileWs extends BaseWs{

    protected function body($data) {
        ?>     
        <div class="container" >            
            <h2 class="text-center"><?= $data['title'] ?></h2>
            <br>
            <p>User name: <?= $_SESSION['name']?></p>
            <p>User mail: <?= $_SESSION['mail']?></p>
            <a href="<?= 'task' ?>" class="btn btn-default">Task list</a>
            <a href="<?= 'change-password' ?>" class="btn btn-default">Change password</a>
            <a href="<?= 'logout'?>" class="btn btn-default">Logout</a>
        </div>
        <?php        
    }
}
