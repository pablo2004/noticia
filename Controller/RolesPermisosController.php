<?php

App::uses('AppController', 'Controller');

class RolesPermisosController extends AppController
{
     
     private $modulePassword = 'rUcURMp33rMR1DEV';

     public function __construct($request = null, $response = null) 
     {
          $this->setModel('RolPermiso');
          $this->setName("RolesPermisos");
          parent::__construct($request, $response);
          $this->startController();
     }

     public function inserta($id, $field = "rol_id", $return = false)
     {    
          parent::inserta($id, $field, $return);
     }

     public function index($id = null, $limit = 10)
     {
          parent::lista($id, "rol_id", $limit);
     }

     public function beforeRender()
     {
          parent::beforeRender();
          $this->set("controladores", self::getControllerList());
     }

     public function beforeFilter() 
     {
          parent::beforeFilter();
          $this->Auth->allow('instalar');
     }
      
     public function instalar()
     {
          $access = (isset($_POST['access'])) ? trim($_POST['access']) : "";
          $content = "";
          $find_acos = $this->getModel()->Rol->find('all');
          $find = sizeof($find_acos);
           
          if(strcmp($access, $this->modulePassword) == 0)
          {
               if($find == 0)
               {
                    $Permiso = $this->getModel();
                    $Usuario = AppController::_newInstance('Usuario');
                    $Particion = AppController::_newInstance('Particion');
                    $Rol = $this->getModel()->Rol;

                    $Permiso->off('Log');
                    $Usuario->off('Log');
                    $Rol->off('Log');

                    $Particion->create();
                    $Particion->save(array('Particion' => array('nombre' => 'Sistema Base')), false);
                    $particion_id = $Particion->getInsertID(); 
                
                    $Rol->create();          
                    $Rol->save(array('Rol' => array('nombre' => 'Administrador', 'url' => '/logs')));
                    $rol_id = $Rol->getInsertID(); 

                    $Permiso->create();          
                    $Permiso->save(array('RolPermiso' => array('rol_id' => $rol_id, 'controlador' => 'Todo', 'accion' => 'todo', 'permitir' => 1)), false);
                
                    $email = "base@sistema.com";
                    $password = uniqid();
                    $Usuario->create();          
                    $Usuario->save(array('Usuario' => array('particion_id' => $particion_id, 'rol_id' => $rol_id, 'nombre' => 'John', 'apellido_paterno' => 'Smith', 'apellido_materno' => 'Smith', 'genero' => 0, 'curp' => 'AAAAAAAAAAAAAAAAAA', 'estado_civil' => 0, 'email' => $email, 'password' => $password, 'fecha_nacimiento' => '1980-01-01', 'activo' => 1)), false);
                     
                    $content .= '<div class="alert alert-success">&iexcl;Instalaci&oacute;n Completada!</div>';
                    $content .= '<div class="alert alert-info">EMail: '.$email.'</div>';
                    $content .= '<div class="alert alert-info">Password: '.$password.'</div>';
               }
               else
               {
                    $content = '<div class="alert alert-error">El sistema ya esta configurado.</div>';
               }
          }
          else
          {
               $content .= '<form role="form" method="POST" action="'.Router::url($this->getAttr("controllerPath")).'instalar">';  
               $content .= '<div class="form-group">
		                <label for="access">Clave de Instalaci&oacute;n</label>
				<div class="controls">
		                     <input class="form-control" name="access" maxlength="50" type="password" id="access"/>
				</div>
			   </div>';
               $content .= '<div class="form-actions">
		                <button class="btn btn-primary" type="submit"><i class="icon-ok icon-white"></i> Instalar</button>
				<a href="'.Router::url($this->getAttr("controllerPath")).'" class="btn btn-danger" type="submit"><i class="icon-remove icon-white"></i> Cancelar</a>
		           </div>';
               $content .= '</form>';  
          }
           
          $this->set("content", $content);
     }
      
     public static function parseController($controlador)
     {
          $controlador = str_replace("Controller.php", "", $controlador);
          return $controlador;
     }
      
     public static function getControllerList()
     {
          App::uses('Folder', 'Utility');
          $folder = new Folder(APP."Controller/");
          $controladores = $folder->find('.*Controller\.php');
          $controladores = array_map("self::parseController", $controladores);
          $controladores_values = array_values($controladores);
          array_unshift($controladores_values, 'Todo');
          $controladores = array_combine($controladores_values, $controladores_values);
          return $controladores;
     }
      
     public static function getControllerMethods($controller)
     {
          $controllerName = $controller."Controller";
          App::uses($controllerName, "Controller");
          $controller = new ReflectionClass($controllerName);
          $methods = $controller->getMethods(ReflectionMethod::IS_PUBLIC);
          $list = array('todo' => 'todo');
        
          foreach($methods AS $method)
          {
               if(strcasecmp($method->class, $controllerName) === 0)
               {
                    $list[$method->name] = $method->name;
               }
          }
        
          return $list;
     }
      
     public function metodos($controller)
     {
          die(json_encode(self::getControllerMethods($controller)));
     }

}

?>
