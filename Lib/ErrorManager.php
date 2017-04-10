<?php


class ErrorManager {

     private $model = "";
     private $errors = array();
     private $messages = array();
     private $path = "";

     public function __construct($model)
     {
          $this->setModel($model);
          $this->initMessages();
          if(!isset($_SESSION)){
               $_SESSION = [];
          }
          $controller = Hash::get($_SESSION, 'Path.controller');
          $action = Hash::get($_SESSION, 'Path.action');
          $this->path = "/".$controller."/".$action;
     }

     public function setModel($model)
     {
          $model = trim($model);
          if(!empty($model))
          {
               $this->model = $model;
          }
     }

     public function getModel()
     {
          return $this->model;
     }

     public function getErrors()
     {
          return $this->errors;
     }

     public function size()
     {
          return sizeof($this->getErrors());
     }

     public function add($error, $toCake = true, $toJS = true)
     {
          $size = $this->size();

          $views = $error->getViews();
          $path = $this->path;

          if(in_array('*', $views) || in_array($path, $views)){
               $this->errors[$size] = array('error' => $error, 'toCake' => $toCake, 'toJS' => $toJS);
          }
          
     }

     public function toCakeArray()
     {
          $errors = $this->getErrors();
          $cakeArray = array();
          $messages = $this->getMessages();
          
          foreach($errors AS $error)
          { 
               $toCake = $error['toCake'];
               $error = $error['error'];
               $error_rule = $error->getRule();
               $field = $error->getField();

               if($toCake)
               {
                    if(sizeof($error_rule) == 0)
                    {
                         $error_rule = array(0 => '');
                    }

                    if($error->isMessageEmpty() && array_key_exists($error_rule[0], $messages))
                    {
                         $message = $this->formatMessage($messages[$error_rule[0]], $error->getRule());
                         $error->setMessage($message);
                    }

                    if(!array_key_exists($error->getField(), $cakeArray))
                    {
                         $cakeArray[$field] = array($this->attributesToArray($error));
                    }
                    else 
                    {
                         $rules = $cakeArray[$field];
                         $rules[] = $this->attributesToArray($error);
                         $cakeArray[$field] = $rules;
                    }

               }
          }
 
          return $cakeArray; 
     }

     public function toJqueryValidation()
     {
          $model = $this->getModel();
          $errors = $this->getErrors();
          $messages = array();
          $rules = array();
          $valids = array('notEmpty' => 'required', 'minLength' => 'minlength', 'maxLength' => 'maxlength', 'between' => 'rangelength', 'email' => 'email', 'url' => 'url', 'date' => 'dateISO', 'numeric' => 'digits', 'equalToField' => 'equalTo'); 
          $localMessages = $this->getMessages();          

          foreach($errors AS $error)
          { 
               $toJS = $error['toJS'];
               $error = $error['error'];
               $field = 'data['.$model.']['.$error->getField().']';
               $rule = $error->getRule();

               if($toJS)
               {
                    if(sizeof($rule) == 0)
                    {
                         $rule = array(0 => '');
                    }

                    if(array_key_exists($rule[0], $valids) && $error->isRequired())
                    {
                         $rule_name = $valids[$rule[0]];
                         $rule_value = null;
                         $rule_message = '';

                         if($error->isMessageEmpty() && array_key_exists($rule[0], $localMessages))
                         {
                              $message = $this->formatMessage($localMessages[$rule[0]], $error->getRule());
                              $error->setMessage($message);
                         }
                    
                         switch($rule_name)
                         {
                              case 'required':
                              case 'email':
                              case 'url':
                              case 'date':
                              case 'dateISO':
                              case 'digits':
                                   $rule_value = true;
                                   $rule_message = $error->getMessage();
                              break;
                              case 'minlength':
                              case 'maxlength':
                                   $rule_value = intval($rule[1]);
                                   $rule_message = $error->getMessage();
                              break;
                              case 'rangelength':
                                   $rule_value = array($rule[1], $rule[2]);
                                   $rule_message = $error->getMessage();
                              break;
                              case 'equalTo':
                                   $rule_value = '#'.$model.$this->formatField($rule[1]);
                                   $rule_message = $error->getMessage();
                              break;
                         }

                         if(!array_key_exists($field, $rules))
                         {
                              $rules[$field] = array($rule_name => $rule_value);
                              $messages[$field] = array($rule_name => $rule_message);
                         }
                         else
                         {
                              $rules[$field][$rule_name] = $rule_value;
                              $messages[$field][$rule_name] = $rule_message;
                         }
                    }
               }
          }
 
          $rules = json_encode($rules);
          $messages = json_encode($messages);
          $json = "{ \"rules\": $rules, \"messages\": $messages }";
          return $json; 
     }

     private function attributesToArray($error)
     {
          $array = array(); 
          $array['rule'] = $error->getRule();    
          $array['message'] = $error->getMessage();   
          $array['on'] = $error->getOn();       
          $array['required'] = $error->isRequired();  
          $array['allowEmpty'] = $error->isAllowEmpty();  
          return $array;
     }

     public function getMessages()
     {
          return $this->messages;
     }

     private function formatMessage($message, $rule)
     {
          $size = sizeof($rule);

          if($size > 1)
          {
               for($i = 1; $i < $size; $i++)
               {
                    $para = $rule[$i];
                    $para = (is_array($para)) ? "(".implode(", ", $para).")" : $para;
                    $message = str_replace('$'.$i, $para, $message);
               }
          }

          return $message;
     }

     private function initMessages()
     {
          $message = array();
          $message['required'] = Lang::ERROR_REQUIRED;
          $message['numeric'] = Lang::ERROR_NUMERIC;
          $message['between'] = Lang::ERROR_BETWEEN;
          $message['date'] = Lang::ERROR_DATE;
          $message['time'] = Lang::ERROR_TIME;
          $message['phone'] = Lang::ERROR_PHONE;
          $message['email'] = Lang::ERROR_EMAIL;
          $message['url'] = Lang::ERROR_URL;
          $message['inList'] = Lang::ERROR_INLIST;
          $message['isUnique'] = Lang::ERROR_UNIQUE;
          $message['notEmpty'] = Lang::ERROR_EMPTY;
          $message['minLength'] = Lang::ERROR_MINLENGHT;
          $message['maxLength'] = Lang::ERROR_MAXLENGHT;
          $message['equalToField'] = Lang::ERROR_EQUALTOFIELD;
          $message['checkUnique'] = Lang::ERROR_MULTIUNIQUE;
          $message = array_merge($message, $this->messages);  
          $this->messages = $message;
     }

     private function formatField($field) 
     {
          $field = str_replace("_", " ", $field);
          $field = ucwords($field);
          $field = str_replace(" ", "", $field);
          return $field;
     }

}


?>
