<?php

App::uses('Component', 'Controller');
App::uses('CakeSession', 'Model/Datasource');

class AuthComponent extends Component
{

     public static $sessionKey = 'Auth.User';
     public static $_user = array();

     public $loginAction = null;
     public $logoutRedirect = null;
     public $loginRedirect = null;

     public $userRol = array();
     public $userRolPermiso = array();

     public $components = array('Session');
     public $controller = null;
     public $cache_prefix = '_0';

     public function __construct($collection, $settings = array())
     {
          parent::__construct($collection, $settings);

          if(!CakeSession::check(self::$sessionKey))
          {
               Cache::delete('userRol'.$this->cache_prefix);
               Cache::delete('userRolPermiso'.$this->cache_prefix);
          }
          else
          {
               $this->cache_prefix = '_'.user('id');
          }
     }

     public function initialize(Controller $controller)
     {
          parent::initialize($controller);

          $this->controller = $controller;
          $userRol = Cache::read('userRol'.$this->cache_prefix);
          $userRolPermiso = Cache::read('userRolPermiso'.$this->cache_prefix);

          if($userRol !== false && $userRolPermiso !== false)
          {
               $this->userRol = $userRol;
               $this->userRolPermiso = $userRolPermiso;
          }
          else
          {
               $rol_id = self::user('rol_id');
               $Rol = AppController::_newInstance('Rol');
               $RolPermiso = AppController::_newInstance('RolPermiso');

               $this->userRol = $Rol->findById($rol_id);
               $this->userRolPermiso = $RolPermiso->find('all', array('recursive' => -1, 'conditions' => array('rol_id' => $rol_id)));

               if($rol_id != null)
               {
                    Cache::write('userRol'.$this->cache_prefix, $this->userRol);
                    Cache::write('userRolPermiso'.$this->cache_prefix, $this->userRolPermiso); 
               }
          }
     }

     public function allow($action)
     {
          $action = trim($action);

          if(!empty($action))
          {
               if(strcmp($action, '*') === 0)
               {
                    $action = 'todo';
               }

               $this->userRolPermiso[] = array('RolPermiso' => array('id' => 0, 'rol_id' => self::rol('id'), 'controlador' => $this->controller->name, 'accion' => $action, 'permitir' => 1, 'fecha_alta' => null, 'fecha_cambio' => null));
          }
     }

     public function deny($action)
     {
          $action = trim($action);

          if(!empty($action))
          {
               if(strcmp($action, '*') === 0)
               {
                    $action = 'todo';
               }
               $this->userRolPermiso[] = array('RolPermiso' => array('id' => 0, 'rol_id' => self::rol('id'), 'controlador' => $this->controller->name, 'accion' => $action, 'permitir' => 0, 'fecha_alta' => null, 'fecha_cambio' => null));
          }
     }

     public function startup(Controller $controller)
     {
          $autorize = false;
          $isAjaxRequest = intval(Hash::get($_GET, 'isAjaxRequest'));

          $permisos = $this->userRolPermiso;
          $name = $controller->name;
          $action = $controller->action;
  
          $autorize = self::hasRecord(Hash::extract($permisos, '{n}.RolPermiso[controlador=Todo][accion=todo][permitir=1]'));
          $checkAll = Hash::extract($permisos, '{n}.RolPermiso[controlador='.$name.']');

          if(self::hasRecord($checkAll))
          {
               $i = ($autorize) ? 1 : 0;

               foreach($checkAll AS $check)
               {
                    if($check['accion'] == $action || $check['accion'] == 'todo')
                    {
                         if($check['permitir'] == 1)
                         {
                              $i++;
                         }
                         else
                         {
                              $i--;
                         }
                    }
               }

               $autorize = ($i >= 1) ? true : false;
          }

          if(!$autorize)
          {
               
               $params = $this->controller->params;
               $url = $params['controller']."/".$params['action'];
               if(strcasecmp($url, "usuarios/login") == 0 || strcasecmp($url, "usuarios/logout") == 0)
               {
                    $url = "";
               }
               else
               {
                    $url = "/".$this->controller->params->url;
               }

               CakeSession::write("url", $url);

               if(!CakeSession::check(self::$sessionKey))
               {
                    $this->controller->redirect($this->logoutRedirect);
               }
               else
               {
                    if($isAjaxRequest == 0)
                    {
                         $this->Session->setFlash('<i class="fa fa-times"></i> Error: Acceso no autorizado.', 'failure');
                         $this->controller->redirect($this->loginRedirect);
                    }
                    else
                    {
                         die('<div class="alert alert-danger"><i class="fa fa-times"></i> Error: Acceso no autorizado.</div>');
                    }
               }
          }

     }

     public static function hasRecord($array)
     {
          $return = false;
 
          if(is_array($array))
          {
               if(sizeof($array) >= 1)
               {
                    $return = true;
               }
          }

          return $return;
     }

     public function login($user = null) 
     {
          $return = false;

          if($user != null) 
          {
               if(is_array($user))
               {
                    if(sizeof($user) >= 1)
                    {
                         $return = true;
                         $this->Session->renew();
                         $this->Session->write(self::$sessionKey, $user['Usuario']);
                         $this->Session->write(self::$sessionKey.".Rol", $user['Rol']);
                    }
               }
          }

          return $return;
     }

     public function logout() 
     {
          $particion_id = user('particion_id');
          $logout = Router::normalize($this->logoutRedirect);
          $logout = (!empty($particion_id)) ? $logout."?particion_id=".$particion_id : $logout;

          Cache::delete('userRol'.$this->cache_prefix);
          Cache::delete('userRolPermiso'.$this->cache_prefix);
          $this->Session->delete(self::$sessionKey);
          $this->Session->delete(self::$sessionKey.".Rol");
          $this->Session->renew();
          return $logout;
     }

     public static function user($key = null) 
     {
          if(!CakeSession::check(self::$sessionKey)) 
          {
               return null;
          }

          $user = CakeSession::read(self::$sessionKey);        

          if($key === null) 
          {
               return $user;
          }

          return Hash::get($user, $key);
     }

     public static function rol($key = null) 
     {
          if (!CakeSession::check(self::$sessionKey.".Rol")) 
          {
               return null;
          }

          $rol = CakeSession::read(self::$sessionKey.".Rol");

          return Hash::get($rol, $key);
     }

     public static function password($password) 
     {
          return Security::hash($password, null, true);
     }
}

?>