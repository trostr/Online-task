<?php

/**
 * Description of LoginWs
 *
 * @author Petr
 */
class LoginWs extends BaseWs{
    
    protected function body($data) {
        ?>     
        <div class="container" >            
            <h2 class="text-center"><?= $data['title'] ?></h2>
            <?php
            $this->showArray($data['form']);
            ?>
            <br><a href="<?= 'registration' ?>">New registration</a><br>
        </div>
        <?php        
    }
}
