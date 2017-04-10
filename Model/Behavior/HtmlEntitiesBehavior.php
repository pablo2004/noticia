<?php

class HtmlEntitiesBehavior extends ModelBehavior
{
     private $field = '';

     public function HtmlEntitiesBehavior($field = '')
     {
          $this->setField($field);
     } 

     public function setField($field)
     {
          $field = trim($field);
          if(!empty($field))
          { 
               $this->field = $field;
          }
     }

     public function getField()
     {
          return $this->field;
     }

     public static function getAcutes()
     {
          return array('&aacute;', '&eacute;', '&iacute;', '&oacute;', '&uacute;', '&Aacute;', '&Eacute;', '&Iacute;', '&Oacute;', '&Uacute;', '&ntilde;', '&Ntilde;', '&uuml;');
     }
	  
     public static function getNormals()
     {
          return array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N', 'u');
     }

     public function afterFind(Model $model, $result, $primary = false)
     {
          $data = array();

          $field = $this->getField();
          $name = $model->name;

          if(empty($field))
          {
               $field = $model->displayField;
          }
		   
          foreach($result AS $k => $record)
          {
               $record[$name][$field] = str_replace(HtmlEntitiesBehavior::getAcutes(), HtmlEntitiesBehavior::getNormals(), $record[$name][$field]);
               $data[$k] = $record;
          }
		   
          return $data;
     }
	  
}

?>
