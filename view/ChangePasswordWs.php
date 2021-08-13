<?php

/**
 * Description of ChangePasswordWs
 *
 * @author Petr
 */
class ChangePasswordWs extends BaseWs{
    
    protected function body($data) {
        ?>     
        <div class="container" >            
            <h2 class="text-center"><?= $data['title'] ?></h2>
            <?php
               // var_dump($data); 
            //if(isset($data['parents_line'])){ echo $data['parents_line'].'<br><br>'; } 
            $this->showArray($data['form']);
            //if(isset($data['remove_link'])){ echo($data['remove_link']); }
            ?>        
        </div>
        <?php        
    }
}
