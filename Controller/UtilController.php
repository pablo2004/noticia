<?php

App::uses('AppController', 'Controller');

class UtilController extends AppController
{

     public $name = 'Util';
     public $layout = null;
     public $viewPath = null;

     public function beforeFilter() 
     {
          parent::beforeFilter();
          $this->Auth->allow('*');
     }

     public function subirArchivo()
     { 
          set_time_limit(60);
          ini_set("memory_limit", "32M");
          die($this->File->fileUpload($_POST));
     }

     public function subirImagen()
     {
          set_time_limit(60);
          ini_set("memory_limit", "32M");
          die($this->File->imageUpload($_POST));
     }

     public function config($name, $value)
     {
          App::uses('Sanitize', 'Utility');
          $data = array('result' => 0);
          $name = Sanitize::clean(trim($name));
          $value = Sanitize::clean(trim($value));

          if(!empty($name))
          {
               $this->Session->write("Config.".$name, $value);
               $data['result'] = 1;
          }
          
          die(json_encode($data));
     }
     
}

?>