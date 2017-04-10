<?php


class ErrorField {

     private $field = '';
     private $allowEmpty = false;
     private $on = '';
     private $required = true;
     private $message = '';
     private $rule = '';
     private $views = [];

     public function __construct($field, $rule = array(), $views = array('*'), $required = true, $allowEmpty = false, $message = "")
     {
          $this->setField($field);
          $this->setRule($rule);
          $this->setRequired($required);
          $this->allowEmpty($allowEmpty);
          $this->setMessage($message);
          $this->setViews($views);
     }

     public function setViews($views){
          if(is_array($views)){
               $this->views = $views;
          }
     }

     public function getViews(){
          return $this->views;
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

     public function allowEmpty($allow = true)
     {
          if(is_bool($allow))
          {
               $this->allowEmpty = $allow;
          }
     }

     public function isAllowEmpty()
     {
          return $this->allowEmpty;
     }

     public function setOn($on)
     {
          $on = strtolower(trim($on));
          $alloweds = array('create', 'update', '');

          if(in_array($on, $alloweds))
          {
               $this->on = $on;
          }
     }

     public function onCreate() 
     {
          $this->setOn('create');
     }

     public function onUpdate() 
     {
          $this->setOn('update');
     }

     public function getOn()
     {
          return $this->on;
     }

     public function setRequired($required = true)
     {
          if(is_bool($required))
          {
               $this->required = $required;
          }
     }

     public function isRequired()
     {
          return $this->required;
     }

     public function setMessage($message)
     { 
          $message = trim($message);
          if(!empty($message))
          {
               $this->message = $message;
          }
     }

     public function getMessage()
     {
          return $this->message;
     }

     public function isMessageEmpty()
     {
          $message = $this->getMessage();
          return (empty($message)) ? true : false;
     }

     public function setRule($rule = array())
     {
          if(is_array($rule))
          {
               $this->rule = $rule;
          }
     }

     public function getRule()
     {
          return $this->rule;
     }
}


?>
