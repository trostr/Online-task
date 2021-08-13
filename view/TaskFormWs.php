<?php

/**
 * Description of TaskFormWs
 *
 * @author Petr
 */
class TaskFormWs extends BaseWs{
    
    protected function body($data) {
        ?>     
        <div class="container" >            
            <?php
               // var_dump($data); 
            if(isset($data['parents_line'])){ echo $data['parents_line'].'<br><br>'; } 
            $this->showArray($data['form']);
            if(isset($data['remove_link'])){ echo($data['remove_link']); }
            ?>        
        </div>
        <?php        
    }
}
