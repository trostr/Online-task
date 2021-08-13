<?php

/**
 * Description of TaskWs
 *
 * @author Petr
 */
class TaskWs extends BaseWs{
    
    protected function body($data) {
        ?>     
        <div class="container" >            
            <?php
            if(isset($data['parents_line'])){ echo $data['parents_line'].'<br><br>'; } 
            if(isset($data['task_list']) && $data['task_list'] !== false){ $this->showArray($data['task_list']); }
            if(isset($data['add_link'])){ echo $data['add_link']; }
            ?>        
        </div>
        <?php        
    }
}
