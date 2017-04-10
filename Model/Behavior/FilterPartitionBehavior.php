<?php

App::uses("FilterBehavior", "Model/Behavior");

class FilterPartitionBehavior extends FilterBehavior
{

     public function setup(Model $model, $config = array())
     {
     	  $particion_id = user('particion_id');

          if($particion_id > 1)
          {
               $this->addFilterBefore($model->alias, $model->alias.".particion_id = '".$particion_id."'");
          }
     }

}

?>
