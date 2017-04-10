<?php

App::uses('Model', 'Model');
App::uses('Lang', 'Lib');
App::uses('ErrorManager', 'Lib');
App::uses('ErrorField', 'Lib');
App::uses('ModelFilter', 'Lib');
App::uses('Attributes', 'Lib');

class AppModel extends Model 
{
     use Attributes;
     const isPhone = "/\(([0-9]{3})\)+ +([0-9]{3})+\-([0-9]{4})$/is";

     private $errorManager = null;
     public $displayCache = false;
     public $belongsTo = array();
     public $hasOne = array();
     public $cacheQueries = true;

     public function __construct($id = false, $table = null, $ds = null)
     {
          parent::__construct($id, $table, $ds);
          $this->addVirtualField('fecha_alta_dia', 'DATE_FORMAT('.$this->alias.'.fecha_alta, "%Y-%m-%d")');
          $this->addVirtualField('fecha_alta_mes', 'DATE_FORMAT('.$this->alias.'.fecha_alta, "%Y-%m")');
          $this->addVirtualField('fecha_alta_hora', 'DATE_FORMAT('.$this->alias.'.fecha_alta, "%H:%i")');
          $this->addVirtualField('fecha_cambio_dia', 'DATE_FORMAT('.$this->alias.'.fecha_cambio, "%Y-%m-%d")');
          $this->addVirtualField('fecha_cambio_mes', 'DATE_FORMAT('.$this->alias.'.fecha_cambio, "%Y-%m")');
          $this->addVirtualField('fecha_cambio_hora', 'DATE_FORMAT('.$this->alias.'.fecha_cambio, "%H:%i")');

          $this->setAttr("noSanitize", array());
          $this->setAttr("noApi", array());
          $this->setAttr("tableHeaders", array());
          $this->setAttr("filters", array());
          $this->setAttr("orders", array());
          $this->setAttr("data", array());
          $this->setAttr("errors", array());
          $this->setAttr("export", array());
          $this->setAttr("controllerTitle", "");
          $this->setAttr("controllerTemplate", "");
          $this->setAttr("controllerPath", "");
          $this->setAttr("controllerUpload", "");
          $this->setAttr("controllerRemove", "");
          $this->setAttr("controllerDownload", "");
     }

     public function addCatalog($name, $foreignKey)
     {
          $conditions = array($this->alias.".".$foreignKey." = ".$name.".valor", $name.".nombre =" => $name);
          $hasOne = array($name => array('className' => 'Catalogo', 'foreignKey' => false, 'conditions' => $conditions));
          $this->bindModel(array('hasOne' => $hasOne));
     }

     public function addVirtualField($field, $format)
     {
          $field = trim($field);
          $format = trim($format);
          $this->virtualFields[$field] = $format;
     }

     public function off($string)
     {
          $behaviors = $this->Behaviors;
          $string = trim($string);
          $behaviors->disable($string);
     }

     public function on($string)
     {
          $behaviors = $this->Behaviors;
          $string = trim($string);
          $behaviors->enable($string);
     }

     public function setErrorManager($manager)
     {
          if(is_a($manager, 'ErrorManager'))
          {
               $this->errorManager = $manager;
               $this->validate = $manager->toCakeArray();
          }
     }

     public function getErrorManager()
     {
          return $this->errorManager;
     }

     public function getJSONValidations()
     {
          $return = "'rules': {}, 'messages': {}";
          $manager = $this->getErrorManager();
          if($manager !== null)
          {
               $return = $manager->toJqueryValidation();
          }
 
          return $return;
     }

     public function sanitizeData($data)
     {
          $noSanitize = $this->getAttr('noSanitize');
          $excludes = array();

          foreach($noSanitize AS $input)
          {
               $excludes[$input] = Hash::get($data, $input);
          }

          App::uses('Sanitize', 'Utility');
          $data = Sanitize::clean($data, array('remove_html' => true, 'encode' => false));

          foreach($excludes AS $path => $value)
          {
               $data = Hash::insert($data, $path, $value);
          }

          return $data;
     }

     public function saveData($data, $valid = true)
     {
          $data = $this->sanitizeData($data);

          $result = array();
          $save = $this->save($data, $valid);
          $lastId = $this->lastId();
          
          $result['stored'] = (sizeof($save) > 0) ? true : false;
          $errors = ($result['stored']) ? array() : $this->invalidFields();
          $result['id'] = ($lastId) ? $lastId : $this->id;
          $result['data'] = $save;
          $result['errors'] = $errors;
          $result['export'] = $this->getAttr('export');
          $result['errorExists'] = (sizeof($errors) > 0) ? true : false;
          $result['viewUrl'] = ($lastId) ? Router::url($this->getAttr('controllerPath'))."editar/".$lastId : "";

          return $result;
     }

     public function returnData($id = 0, $saved = true, $data = array(), $url = "")
     {
          $result = array();
          $result['id'] = $id;
          $result['stored'] = $saved;
          $result['data'] = $data;
          $result['export'] = $this->getAttr('export');
          $result['errors'] = $this->getAttr('errors');
          $result['errorExists'] = (sizeof($this->getAttr('errors')) > 0) ? true : false;
          $result['viewUrl'] = $url;

          return $result;
     }

     public function lastId()
     {
          return $this->getInsertID();
     }

     public function getFormatCallback($record)
     {
          return "";
     }

     /////////////////////////////////////////////////////////
     // CUSTOM VALIDATIONS
     /////////////////////////////////////////////////////////

     public function equalToField($fields, $compare)
     {
          $field = "";
          $model = $this->name;
          $compare = trim($compare); 
          $compare = (isset($this->data[$model][$compare])) ? $this->data[$model][$compare] : "";
          
          foreach($fields AS $value)
          {
               $field = $value;
               break;
          }

          return (strcmp($field, $compare) === 0) ? true : false;
     }

     public function checkUnique($data, $fields)
     {
          $unique = array();
	  
	     if(!is_array($fields))
	     {
	          $fields = array($fields);
	     }

	     foreach($fields as $key)
	     {
	          $unique[$key." ="] = $this->data[$this->name][$key];
	     }

	     if(isset($this->data[$this->name][$this->primaryKey]))
	     {
	          $unique[$this->primaryKey." !="] = $this->data[$this->name][$this->primaryKey];
	     }

          $find = sizeof($this->find("all", array('conditions' => $unique, 'recursive' => -1)));

	     return ($find == 0) ? true : false;
     }

     /* CACHE PAGINATOR */

     public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) 
     {
          $args = func_get_args();
          $particion_id = intval(user('particion_id'));
          $key_fetch = "pagination_".$particion_id."_".$this->alias."_".sha1(implode("", array_map("serialize", $args)))."_fetch";
          $clearcache = intval(Hash::get($_GET, 'clearcache')); 
          $compact = compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group');

          if($clearcache == 1 || !$this->displayCache)
          {
               $find = $this->find('all', $compact);
               Cache::write($key_fetch, $find);
          }
          else
          {
               $find = Cache::read($key_fetch);

               if($find == null)
               {
                    $find = $this->find('all', $compact);
                    Cache::write($key_fetch, $find);
               }
          }

          return $find;
     }

     public function paginateCount($conditions = null, $recursive = 0, $extra = array()) 
     {
          $args = func_get_args();
          $particion_id = intval(user('particion_id'));
          $key_count = "pagination_".$particion_id."_".$this->alias."_".sha1(implode("", array_map("serialize", $args)))."_count";
          $clearcache = intval(Hash::get($_GET, 'clearcache'));
          $compact = compact('conditions', 'recursive');
          $count = 0;

          if($clearcache == 1 || !$this->displayCache)
          {
               $count = $this->find('count', $compact);
               Cache::write($key_count, $count);
          }
          else
          {
               $count = Cache::read($key_count);

               if($count == null)
               {
                    $count = $this->find('count', $compact);
                    Cache::write($key_count, $count);
               }
          }

          return $count;
     }

}
