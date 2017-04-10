<?php
  

class AutoFieldBehavior extends ModelBehavior
{

     private $onCreate = array();
     private $onUpdate = array();

     public function setup(Model $model, $config = array())
     {
          $this->initDefaults();
     }

     public function addCreateField($field, $value)
     {
          $field = trim($field);
          $value = trim($value);

          if(!empty($field))
          {
               $this->onCreate[$field] = $value;
          }
     }

     public function addUpdateField($field, $value)
     {
          $field = trim($field);
          $value = trim($value);

          if(!empty($field))
          {
               $this->onUpdate[$field] = $value;
          }
     }

     public function getCreateField()
     { 
          return $this->onCreate;
     }

     public function getUpdateField()
     { 
          return $this->onUpdate;
     }
     
     public function beforeSave(Model $Model, $options = array())
     {
          $name = $Model->name;

          if(!$Model->exists())
          {
               $fields = $this->getCreateField();
          }
          else
          {
               $fields = $this->getUpdateField();
          }


          foreach($fields AS $index => $value)
          {
               if($Model->hasField($index))
               {
	               if(empty($Model->data[$name][$index]))
		          {
		               $Model->data[$name][$index] = $value;
		          }
               }
          }

          return true;
     }

     public function initDefaults()
     {
          $date = date("Y-m-d H:i:s");
          $this->addCreateField('fecha_alta', $date);
          if(isUser()){
               $this->addCreateField('usuario_id', user('id')); 
               $this->addCreateField('particion_id', user('particion_id'));
          }
          $this->addCreateField('oid', uniqid()); 
          $this->addUpdateField('fecha_cambio', $date);
     }
}

?>
