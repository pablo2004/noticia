<?php


class FilterBehavior extends ModelBehavior
{

     private $filters_before = array();
     private $filters_after = array();

     public function setup(Model $model, $config = array())
     {

     }

     public function addFilterBefore($model, $condition)
     {
          $model = trim($model);
          $condition = trim($condition);

          if(!empty($model) && !empty($condition))
          {
               $this->filters_before[$model][] = $condition;
          }
     }

     public function addFilterAfter($model, $callback)
     {
          $callback = trim($callback);

          if(is_callable($callback))
          {
               $this->filters_after[$model][] = $callback;
          }
     }

     public function getFiltersBefore($model = "")
     {
          $model = trim($model);
          $filters = $this->filters_before;
          
          if(!empty($model))
          {
               if(array_key_exists($model, $filters))
               {
                    $filters = $filters[$model];
               }
               else
               {
                    $filters = array();
               }
          }

          return $filters;
     }

     public function getFiltersAfter($model)
     {
          $model = trim($model);
          $filters = $this->filters_after;
          
          if(!empty($model))
          {
               if(array_key_exists($model, $filters))
               {
                    $filters = $filters[$model];
               }
               else
               {
                    $filters = array();
               }
          }

          return $filters;
     }

     public function beforeFind(Model $model, $data)
     {
          $name = $model->name;
          $filters = $this->getFiltersBefore($name); 
          $conditions = $data['conditions'];
          $filters_size = sizeof($filters);

          foreach($filters AS $field => $filter)
          {
               $conditions[] = $filter; 
          }
          
          $data['conditions'] = $conditions;

          return $data;
     }

     public function afterFind(Model $model, $data, $primary = false)
     {
          $filters = $this->getFiltersAfter($model->name);
          $name = $model->name;
          $return = array();
          $filters_size = sizeof($filters);
          
          if($filters_size > 0)
          {
               foreach($data AS $key => $record)
               {
                    $add = true;

                    for($i = 0; $i < $filters_size; $i++)
                    {
                         $filter = $filters[$i];
                         if(!call_user_func($filter, $record))
                         { 
                              $i = $filters_size + 1;
                              $add = false;
                         }
                    }

                    if($add) 
                    {
                         $return[$key] = $record;
                    }
               }
           
               unset($data); 
          }
          else
          {
               $return = $data;
          }

          return $return;
     }


}

?>
