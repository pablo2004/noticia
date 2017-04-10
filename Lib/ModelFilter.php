<?php


class ModelFilter
{

     private $data = array();
     private $filters = array();
     public static $conditions = array('=', '<=', '>=', '<', '>', '!=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN');
 
     public function __construct()
     {
          $this->setData($_GET);
     }

     public function setData($data)
     {
          if(is_array($data))
          { 
               if(sizeof($data) > 0)
               {
                    $keys = array_map("self::formatField", array_keys($data));
                    $values = array_values($data);
                    $data = array_combine($keys, $values);
                    $this->data = $data;
               }
          }
     }

     public function getData()
     {
          return $this->data;
     }

     public function setFilters($filters)
     {
          if(is_array($filters))
          {
               $this->filters = $filters;
          }
     }

     public function getFilters()
     {
          return $this->filters;
     }

     public function addFilter($field, $value = null, $condition, $validate = array())
     {
          $field = trim($field);  
          $conditions = trim($condition);

          if(!empty($field) && in_array($condition, self::$conditions) && is_array($validate))
          {
               $filters = $this->getFilters();
               $filters[] = array('field' => $field, 'value' => $value, 'condition' => $condition, 'validate' => $validate);
               $this->setFilters($filters);
          }
     }

     public function formatFilters()
     {
          $data = $this->getData();
          $filters = $this->getFilters();
          $return = array();
          $conditions = self::$conditions;
          App::uses('Validation', 'Utility');

          foreach($filters AS $filter)
          {
               $field = $filter['field'];
               $value = $filter['value'];
               $condition = $filter['condition'];
               $validate = $filter['validate'];
               $continue = true;

               if($value == null)
               { 
                    if(array_key_exists($field, $data))
                    {
                         $value = $data[$field];
                    }
               }

               if(sizeof($validate) > 0)
               { 
                    $params = (array_key_exists(1, $validate)) ? $validate[1] : array();
                    array_unshift($params, $value); 

                    if(!call_user_func_array("Validation::".$validate[0], $params))
                    {
                         $continue = false;
                    }
               }

               if($continue)
               {  
                    switch($condition)
                    {
                         case '=':
                         case '<=':
                         case '>=':
                         case '>':
                         case '<':
                         case '!=':
                              $return[] = array($field.' '.$condition => $value);
                         break;
                         case 'LIKE':
                         case 'NOT LIKE':
                              $return[] = array($field.' '.$condition => '%'.$value.'%');
                         break;
                         case 'IN':
                         case 'NOT IN':
                              $return[$condition] = array($field => $value);
                         break;
                    }
               }
          }

          return $return;
     }

     public static function formatField($field)
     {
          $field = trim($field);
          $parts = explode("_", $field);
          $size = sizeof($parts);

          if($size > 1)
          {
               if($size == 2)
               {
                    $field = str_replace("_", ".", $field);
               }
               else
               {
                    $model = $parts[0];
                    unset($parts[0]);
                    $field = $model.".".implode("_", $parts); 
               }
          }

          return $field;
     }
}


?>
