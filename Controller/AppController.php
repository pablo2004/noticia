<?php

App::uses('Controller', 'Controller');
App::uses('Attributes', 'Lib');

class AppController extends Controller 
{

     use Attributes;     
     public $helpers = array('Number', 'Html', 'Text', 'Form', 'Session', 'Paginator', 'Block', 'Input', 'Format', 'Dom');
     public $components = array(
          'Session',
          'Auth',
          'File',
	     'RequestHandler',
          'Menu',
          'Format',
     );
     
     private $modelName = "";
     private $model = null;
     public $cacheAction = array();

     public function startController()
     {
          $this->setAttrs($this->getModel()->getAttrs('controllerTitle', 'controllerTemplate', 'controllerPath', 'controllerUpload', 'controllerRemove', 'controllerDownload'), false);
     }

     public function setName($name)
     {
          $name = trim($name);
          if(!empty($name))
          {
               $this->name = $name;
          }
     }

     public function setModel($model)
     {
          $model = trim($model);
          if(!empty($model))
          {
               $this->uses = array($model);
               $this->viewPath = $model;
	          $this->setModelName($model);
          }
     }

     public function getModel()
     {
          $model = $this->getModelName();
          return $this->$model;
     }

     public function setModelName($name)
     {
          $name = trim($name);
          if(!empty($name))
          {
               $this->modelName = $name;
          }
     }

     public function getModelName()
     {
          return $this->modelName;
     }

     public function beforeFilter()
     {
          $this->Session->write("Path.controller", $this->request->params['controller']);
          $this->Session->write("Path.action", $this->request->params['action']);
	     parent::beforeFilter();          
          $this->Auth->loginAction = '/usuarios/login/';
          $this->Auth->logoutRedirect = '/usuarios/login/';
          $this->Auth->loginRedirect = '/pages/index/';
     }
     
     public function beforeRender()
     {
          parent::beforeRender();
          if(isUser()){
               $permits = Cache::read("userRolPermiso_".user("id"));
               $permits = ($permits !== null) ? json_encode($permits) : "[]";
          }
          else {
               $permits = "[]";
          }
          
          $this->set("permits", $permits);
          $this->set($this->getAttrs('controllerTitle'));
          $this->set("navBar1", $this->Menu->getMenu(1));
          $this->set("navBar2", $this->Menu->getMenu(2));
     }

     /////////////////////////////////////////////////////////////////////////////
     // UTIL FUNCTIONS
     /////////////////////////////////////////////////////////////////////////////
     
     public static function getRolByName($name)
     {
          $name = trim($name);
          $rol_id = 0; 
          $Rol = self::_newInstance('Rol');
          $getRol = $Rol->findByNombre($name);
  
          if(sizeof($getRol) > 0)
          {
               $rol_id = $getRol['Rol']['id'];
          }
           
          return $rol_id;
     }

     public static function isRol($rol)
     {
          $rol = trim($rol);
          $return = false;
          
          if(isUser())
          {
               if(strcasecmp(rol('nombre'), $rol) == 0)
               {
                    $return = true;
               }
          }

          return $return;
     }
     
     public static function sendMail($site_mail_to, $site_mail_subject, $site_mail_message, $site_mail_from_name = "Sistema", $site_mail_from_mail = "admin@sistema.com", $type = 'api')
     {
          $type = strtolower(trim($type));
          $data = null;

          if(strcmp($type, 'api') === 0)
          {
	          $site_mail_url = "http://api.com/servicios/email/";
	          $site_mail_key = "DqELLW2LkXxPDgdm";
	  
	          $site_mail_message = urlencode(trim($site_mail_message));
	          $site_mail_to = trim($site_mail_to);
	          $site_mail_subject = urlencode(trim($site_mail_subject));
	  
	          $site_mail_from_name = urlencode(trim($site_mail_from_name));
	          $site_mail_from_mail = trim($site_mail_from_mail);
	  
	          $data = "";
	          $url = $site_mail_url."index.php?key=".$site_mail_key."&message=$site_mail_message&to=".$site_mail_to."&subject=$site_mail_subject&from_mail=$site_mail_from_mail&from=$site_mail_from_name";
               $data = file_get_contents($url); 
          }

          if(strcmp($type, 'cake') === 0)
          {
               App::uses('CakeEmail', 'Network/Email');

               $Email = new CakeEmail();
               $Email->config('smtp');
               $Email->from(array($site_mail_from_mail => $site_mail_from_name));
               $Email->to($site_mail_to);
               $Email->subject($site_mail_subject);
               $Email->send($site_mail_message);
               $data = '{"sended": 1, "error": ""}';
          }

	     return $data;
     }
     
     public static function getModelList($name, $conditions = [], $options = [], $join = [])
     {
          $name = trim($name);

          if(is_array($conditions))
	     {
	          $options['conditions'] = $conditions;
	     }
	  
	     $Model = self::_newInstance($name);
          $List = $Model->find('list', $options);

          if(is_array($join)){
               foreach ($join AS $key => $value) {
                    $List[$key] = $value;
               }
               ksort($List);
          }
          
          return $List;
     }

     public static function _newInstance($name)
     {
          $name = trim($name);
          $instance = null;

          if(!empty($name)) 
          {
               App::uses('Model', 'Model');
               App::uses('AppModel', 'Model');
               App::uses($name, 'Model');
               $Reflection = new ReflectionClass($name);
	          $instance = $Reflection->newInstance(array());
          }

          return $instance;
     }

     /////////////////////////////////////////////////////////////////////////////
     // LOCAL LIST
     /////////////////////////////////////////////////////////////////////////////

     public static function getMethods($class)
     {
          $class = trim($class);
          $return = array();

          $class = new ReflectionClass($class);
          $array = $class->getMethods();

          if(is_array($array))
          {
               foreach($array AS $key => $value)
               {
                    $return[$value->name] = $value->name;
               }
          }
 
          return $return;
     }

     public static function formatCatalogQuery($value)
     {
          $value = preg_replace("/\_/", ".", $value, 1);
          return $value;
     }

     public static function intervals($fecha1, $fecha2)
     {
          $fechas = array();
          $fecha1 = new DateTime($fecha1);
          $fecha2 = new DateTime($fecha2);
          $intervalo = new DateInterval('P1D');
          $fecha2 = $fecha2->modify('+1 day');

          $periodos = new DatePeriod($fecha1, $intervalo, $fecha2);

          foreach ($periodos as $fecha) 
          {
               $fecha = $fecha->format('Y-m-d');
               $fechas[$fecha] = $fecha;
          }

          return $fechas;
     }
     public static function checkRol($rol_id, $rols = array())
     {

          $result = false;
          $rols = (!is_array($rols)) ? explode(",", $rols) : $rols;
          $rol_id = intval($rol_id);
          $rols = array_map(function($item){ return intval(trim($item)); }, $rols);
          
          if(in_array(-2, $rols))
          {
               $result = true;
          }
          elseif(in_array(-1, $rols))
          {
               $result = isUser();
          }
          elseif(in_array(0, $rols))
          {
               $result = !isUser();
          }
          elseif(in_array($rol_id, $rols))
          {
               $result = true;
          }

          return $result;
    
     }

     /////////////////////////////////////////////////////////////////////////////
     // START TEMPLATE
     /////////////////////////////////////////////////////////////////////////////

     public function reporte($model, $name, $fields = [], $options = [], $ModelFilter = null)
     {
          //$fields = "nombre" => "Modelo.nombre"
     	  //$ModelFilter->addFilter("Modelo.nombre", null, 'LIKE', array('notEmpty'));
          
          $on = intval(Hash::get($_GET, 'on'));

          $fieldNames = array_keys($fields);
          $fieldValues = array_values($fields);
          $data = [];

          $optionsDefaults = [
               'order' => [$model->name.'.'.$model->primaryKey => 'ASC'],
               'conditions' => [],
               'fields' => $fieldValues
          ];

          $options = array_merge($optionsDefaults, $options);
          
          if(is_a($ModelFilter, "ModelFilter")){
                $options['conditions'] = array_merge($options['conditions'], $ModelFilter->formatFilters());
          }

          if($on == 1){

               $records = $model->find("all", $options);

               $data = array_map(function($value) use($fieldValues){
               	    $row = [];
               	    foreach($fieldValues AS $path){
               	    	array_push($row, Hash::get($value, $path));
               	    }
                    return $row;
               }, $records);

               array_unshift($data, $fieldNames);

               error_reporting(E_ALL);
               ini_set('display_errors', TRUE);
               ini_set('display_startup_errors', TRUE);

               if (PHP_SAPI == 'cli'){
                    die('This example should only be run from a Web Browser');
               }

               App::import("Vendor", "phpexcel/PHPExcel"); 
               $objPHPExcel = new PHPExcel();

               $objPHPExcel->getProperties()->setCreator("PHPExcel")
                                    ->setLastModifiedBy("PHPExcel")
                                    ->setTitle("Reporte")
                                    ->setSubject("Reporte")
                                    ->setDescription("Reporte")
                                    ->setKeywords("reporte")
                                    ->setCategory("reporte");

               $objPHPExcel->setActiveSheetIndex(0)->fromArray($data, NULL, 'A1');
               $objPHPExcel->getActiveSheet()->setTitle('Hoja 1');
               $objPHPExcel->setActiveSheetIndex(0);

               header('Content-Type: application/vnd.ms-excel');
               header('Content-Disposition: attachment;filename="'.$name.'.xls"');
               header('Cache-Control: max-age=0');
               header('Cache-Control: max-age=1');
               header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
               header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
               header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
               header('Pragma: public'); // HTTP/1.0

               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
               $objWriter->save('php://output');
               exit;
          }

     }

     public function api()
     {
          header('Access-Control-Allow-Origin: *');
          $particion_id = intval(Hash::get($_GET, 'particion_id'));
          $api = trim(Hash::get($_GET, 'api'));

          $Particion = AppController::_newInstance("Particion");
          $Particion->id = $particion_id;
          $particion = $Particion->read(); 

          if($Particion->exists()) {
               if(strcmp($api, $particion['Particion']['oid']) !== 0){
                    die('{"result": "Acceso Denegado"}');
               }
          }
          else{
               die('{"result": "Acceso Denegado"}');
          }
     }

     public function buscar()
     {
     }

     public function item($id)
     {
          $this->getModel()->id = $id;

          if($this->getModel()->exists())
          {
               $data = $this->getModel()->read();
               echo $this->getModel()->getFormatCallback($data);
          }

          exit;
     }

     public function index($limit = 10)
     {
          $model = $this->getModelName();
          $limit = intval($limit);
          $this->Session->write('limit', intval($limit));

          $options = array();
          $options['conditions'] = $this->getModel()->getAttr('filters');
          $options['order'] = array($model.'.'.$this->getModel()->primaryKey => 'DESC');
          $options['limit'] = $this->Session->read('limit');
          $this->paginate = $options;
          $registros = $this->paginate($model); 
          $cabeceras = $this->getModel()->getAttr('tableHeaders');
          $callback = array($this->getModel(), "getFormatCallback");
          $orden = $this->getModel()->getAttr('orders');
          $this->set(compact('cabeceras', 'registros', 'callback', 'orden'));
     }

     public function lista($id, $field, $limit = 10, $paginate = true)
     {
          header('Content-Type: text/html; charset=utf-8');
          $model = $this->getModelName();
          $limit = intval($limit);

          $options = array();
          $options['conditions'] = $this->getModel()->getAttr('filters');
          $options['conditions'][] = array($model.".$field =" => $id);
          $options['order'] = array($this->getModel()->order);
          if($limit > 0)
          {
               $options['limit'] = $limit;
          }
          else
          {
               $limit = 50;
          }

          if($paginate)
          {
               $this->paginate = $options;
               $registros = $this->paginate($this->getModelName()); 
          }
          else
          {
               $registro = $this->getModel->find("all", $options);  
          }

          $cabeceras = $this->getModel()->getAttr('tableHeaders');
          $callback = array($this->getModel(), "getFormatCallback");
          $orden = $this->getModel()->getAttr('orders');
          $this->set(compact('cabeceras', 'registros', 'callback', 'orden', 'id', 'model'));
     }

     public function alta($return = false)
     {
          if($this->request->is('post'))
          {
                $data = $this->getModel()->saveData($this->request->data);

                if($return){
                     $this->render(false);
                     return json_encode($data);
                }
                else{
                     die(json_encode($data));
                }

          }
          else
          {
               $this->set('validate', $this->getModel()->getJSONValidations());
          }
     }

     public function editar($id = null, $cache = 1, $return = false)
     {
          $this->getModel()->id = $id;

          if($this->getModel()->exists())
          {
               $key = strtolower($this->getModel()->name."_".$id);

               if($this->request->is('get'))
               { 
                    header('Content-Type: text/html; charset=utf-8');
                    if($cache == 1)
                    {
                         $record = Cache::read($key);
                         if($record == null)
                         {
                              $record = $this->getModel()->read();
                              Cache::write($key, $record);
                         }
                    }
                    else
                    {
                         Cache::delete($key);
                         $record = $this->getModel()->read();
                    }
                    
                    if(sizeof($record) > 0)
                    {
                         $this->request->data = $record;
                         $validate = $this->getModel()->getJSONValidations();
                         $this->set(compact('record', 'validate', 'id')); 
                    }
                    else
                    {
                         $this->Session->setFlash('Error: No tienes permiso de ver el registro.', 'failure');
                         $this->redirect($this->getModel()->getAttr('controllerPath'));
                    }
               }
               else
               {
                    $data = $this->getModel()->saveData($this->request->data);

                    if($data['stored'])
                    {
                         Cache::delete($key);
                         Cache::write($key, $this->getModel()->read());
                    }

                    if($return){
                         $this->render(false);
                         return json_encode($data);
                    }
                    else{
                         die(json_encode($data));
                    }
               }
          }
          else
          {
               $this->Session->setFlash('Error: No se encontro el registro.', 'failure');
               $this->redirect($this->getModel()->getAttr('controllerPath'));
          }
     }

     public function borrar($id)
     {
          if($this->request->is('get'))
          {
               die('Acceso no permitido.');
          }
          $message = (!$this->getModel()->delete($id)) ? "Error: No se borro el registro." : "";
          die ('{"error": "'.$message.'"}'); 
     }

     public function eliminar($ids)
     {
          $ids = trim($ids);

          if(!empty($ids))
          { 
               $ids = explode(",", $ids);
               foreach($ids AS $id)
               {
                    $this->getModel()->id = $id;
                    $this->getModel()->delete();
               }
          }

          exit;
     }

     protected function altaRelacion($id, $parent, $child, $copy = array('particion_id'))
     {
          $parent = (isset($this->$parent)) ? $this->$parent : self::_newInstance($parent);
          $parent->id = $id;
          $data = false;

          if($parent->exists())
          {
               $relation = $parent->hasOne[$child];
               $child = $parent->$child;
               //$key = strtolower($child->alias."_".$id);

               $parent_data = $parent->read();
               $data = $child->find('first', array('conditions' => array($child->name.".".$relation['foreignKey']." = " => $id)));

               if(sizeof($data) <= 0)
               {
                    $save = array();
                    $save[$relation['foreignKey']] = $id;
                    foreach($copy AS $field)
                    {
                         $save[$field] = $parent_data[$parent->name][$field];
                    }

                    $child->create();
                    $child->save(array($child->name => $save), false);
                    $child->id = $child->lastId();
                    $data = $child->read();
               }
               else
               {
                    $child->id = $data[$child->name]['id'];
               }

               $data['Padre'] = $parent_data;

               if($this->request->is('get'))
               {
                    $this->request->data = $data;
                    $validate = $child->getJSONValidations();
                    $this->set(compact('id', 'validate'));
               }
               else
               {
                    $data = $child->saveData($this->request->data);
                    die(json_encode($data));
               }  

          }

          return $data;
     }

     protected function inserta($id, $field = '', $return = false)
     {    
          if($this->request->is('post'))
          {
                $field = trim($field);
                $this->request->data[$this->getModel()->name][$field] = $id;
                $data = $this->getModel()->saveData($this->request->data);

                if($return){
                     $this->render(false);
                     return json_encode($data);
                }
                else{
                     die(json_encode($data));
                }
          }
          else
          {
               header('Content-Type: text/html; charset=utf-8');
               $validate = $this->getModel()->getJSONValidations();
               $this->set(compact('id', 'validate'));
          }
     }

     public function campo($id = null, $field, $value = null)
     {
          $this->getModel()->id = $id;
          $result = 0;

          if($value == null)
          {
               $value = Hash::get($_POST, 'value');
          }

          if($this->getModel()->exists())
          {
               App::uses('Sanitize', 'Utility');
               $value = Sanitize::clean($value);
               $this->getModel()->saveField($field, $value);
               $result = 1;
          }

          die('{"result": '.$result.'}');
     }

     public function archivoDescargar($file)
     {
          header('Content-disposition: attachment; filename='.$file);
          readfile(APP.WEBROOT_DIR.$this->getAttr('controllerUpload')."/".$file);
          exit;
     }

     public function archivoBorrar($file)
     {
          App::uses('File', 'Utility');
          if(!empty($file))
          {
               $filePath = APP.WEBROOT_DIR.$this->getAttr('controllerUpload')."/".$file;
               $File = new File($filePath);
               $File->delete();
          }
          exit;
     }

     public function catalog($id, $field, $query = "")
     {
          header('Access-Control-Allow-Origin: *'); 
          ini_set('memory_limit', '256M');

          $options = array();
          $query = trim($query);
          $model = $this->getModel();

          if(!empty($query))
          {
               parse_str($query, $conditions);
               $keys = array_map("self::formatCatalogQuery", array_keys($conditions));
               $values = array_map("trim", array_values($conditions));
               $conditions = array_combine($keys, $values);
               $options['conditions'] = $conditions;
          }

          $model->primaryKey = trim($id);
          $model->displayField = trim($field);
          $data = $model->find("list", $options);

          echo json_encode($data);
          exit;
     }

     public function json($value, $label, $query = "", $limit = 20)
     {
          header('Access-Control-Allow-Origin: *'); 
          ini_set('memory_limit', '32M');
          $term = (array_key_exists("term", $_GET)) ? trim($_GET['term']) : "";
          $query = trim($query);
          $model = $this->getModel();

          $options['limit'] = $limit;
          $options['conditions'] = array($value.' LIKE' => "%$term%");

          if(!empty($query))
          {
               parse_str($query, $conditions);
               $keys = array_map("self::formatCatalogQuery", array_keys($conditions));
               $values = array_map("trim", array_values($conditions));
               $conditions = array_combine($keys, $values);
               $options['conditions'] = array_merge($conditions, $options['conditions']);
          }

          $find = $model->find("all", $options);
          $data = array();
          $i = 0;
  
          $label = explode(".", $label);  
          $value = explode(".", $value); 
          $noApi = $this->getModel()->getAttr('noApi');

          foreach($find AS $record)
          {
               foreach($noApi AS $exclude)
               {
                    $record = Hash::remove($record, $exclude);
               }
               $data[$i]['value'] = $record[$value[0]][$value[1]];
               $data[$i]['label'] = $record[$label[0]][$label[1]];

               foreach($record AS $model => $fields)
               {
                    foreach($fields AS $field_key => $field_value)
                    { 
                         $field_key = Inflector::camelize($field_key);
                         $data[$i][$model.$field_key] = $field_value;
                    }
               }

               $i++;
          }
 
          echo json_encode($data);
          exit;
     }

}

?>