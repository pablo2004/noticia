<?php

App::uses('AppController', 'Controller');

class LogsController extends AppController 
{

     private $actualModel = "";
     private $actualPath = "";
     private $actualFields = [];

     public function __construct($request = null, $response = null) 
     {
          $this->setModel('Log');
          $this->setName("Logs");
          parent::__construct($request, $response);
          $this->startController();
     }

     public function registros($model = "Log")
     {
          $limit = 20;
          $object = AppController::_newInstance($model);
          $fields = array_keys($object->schema());

          $this->actualModel = $model;
          $this->actualFields = $fields;
          $this->actualPath = $object->getAttr("controllerPath");

          $ModelFilter = new ModelFilter();

          foreach($fields AS $field){
               $ModelFilter->addFilter($model.".".$field, null, '=', array('notEmpty'));
          }
         
          $options = array();
          $options['conditions'] = $ModelFilter->formatFilters();
          $options['order'] = array($model.'.'.$object->primaryKey => 'DESC');
          $options['limit'] = $limit;
          $this->paginate = $options;
          $registros = $this->paginate($object); 
          $cabeceras = $fields;
          $callback = array($this, "getFormatCallback");
          $orden = array_combine(array_map(function($field) use($model){ return $model.".".$field; }, $fields), $fields);
          $this->set(compact('cabeceras', 'registros', 'callback', 'orden', 'model', 'fields'));
     }

     public function getFormatCallback($record)
     {
          $format = '';

          if(is_array($record))
          {
               $path = Router::url($this->actualPath);
               $modelName = $this->actualModel;
               $id = $record[$modelName]['id'];
               
               $format .= '<tr>';
               foreach($this->actualFields AS $field)
               {
                    $format .= '<td><a data-pk="'.$id.'" id="'.$modelName.'_'.$field.'_'.$id.'" data-type="text" data-url="'.$path.'campo/'.$id.'/'.$field.'" href="#" class="xeditable">'.$record[$modelName][$field].'</a></td>';
               }
               $format .= '<tr>';
          }

          return $format;
     }

     public function beforeFilter(){
     	$this->Menu->addSubMenu(['padre' => '/logs', 'enlace' => '/logs/?searchAuto=1', 'nombre' => 'Buscar', 'icono' => 'fa fa-search']);
          $this->Menu->addSubMenu(['padre' => '/logs', 'enlace' => '/logs/registros', 'nombre' => 'Registros', 'icono' => 'fa fa-list']);
          parent::beforeFilter();
     }

}

?>
