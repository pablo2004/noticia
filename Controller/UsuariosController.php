<?php

App::uses('AppController', 'Controller');

class UsuariosController extends AppController 
{

     public function __construct($request = null, $response = null) 
     {
          $this->setModel('Usuario');
          $this->setName("Usuarios");
          parent::__construct($request, $response);
          $this->startController();
     }

     public function getRoles()
     {
          $roles = $this->getModel()->Rol->find('list');
          $rol_parent = AuthComponent::user('rol_id');
          $nivel = AppController::_newInstance("RolNivel");
          $return = array(); 
     
          foreach($roles AS $key => $rol)
          {
               if($nivel->isParent($rol_parent, $key))
               {
                    $return[$key] = $rol;
               }
          }
          
          return $return;
     }

     public function alta($return = false)
     {
          parent::alta($return);
          $this->set("roles", $this->getRoles());
     }

     public function editar($id = null, $cache = 1, $return = false)
     {
          parent::editar($id, $cache, $return);
          $roles = $this->getRoles();
          $this->set("roles", $roles);

          $rol_parent = AuthComponent::user('rol_id');
          $nivel = AppController::_newInstance("RolNivel");
     }

     public function buscar()
     {
          parent::buscar();
          $roles = $this->getRoles();
          $roles[''] = 'Seleccionar';
          ksort($roles);
          $this->set("roles", $roles);
     }

     public function login()
     {
          $particion_id = intval(Hash::get($_GET, 'particion_id'));
          $install = $this->getModel()->Rol->find('count');

          if($install == 0)
          {
               $this->redirect('/roles_permisos/install');
          }

          if($particion_id == 0)
          {
               $this->redirect('/usuarios/login/?particion_id=1');
          }

          $RECAPTCHA_PUBLIC_KEY  = RECAPTCHA_PUBLIC_KEY;
          $RECAPTCHA_PRIVATE_KEY = RECAPTCHA_PRIVATE_KEY;
          $recaptcha_html = "";
          App::import("Vendor", "recaptchalib");

          if(!$this->Session->read('Auth.User'))
          {

               if($this->request->is('post'))
               {
                    $recaptcha = true;

                    if(!empty($RECAPTCHA_PRIVATE_KEY))
                    {
                         $response = recaptcha_check_answer($RECAPTCHA_PRIVATE_KEY, $this->RequestHandler->getClientIp(), $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
                         if (!$response->is_valid)
                         {
                              $recaptcha = false;
                              $this->getModel()->addToAttr('errors', 'Captcha incorrecto.', 'captcha');
                         }
                    }

                    if($recaptcha)
                    {
                         $particion = intval($this->request->data['Usuario']['particion_id']);
                         $email = $this->request->data['Usuario']['email'];
                         $password = AuthComponent::password($this->request->data['Usuario']['password']);
                         
                         $usuario = $this->getModel()->findByParticionIdAndEmailAndPasswordAndActivo($particion, $email, $password, 1);
                         
                         if(!$this->Auth->login($usuario))
                         {
                              $this->getModel()->addToAttr('errors', 'Datos de acceso incorrectos.', 'password');
                         }
                         else
                         {
                              $url = CakeSession::read('url');
                              if($url == null && empty($url))
                              {
                                   $url = Router::url($this->Auth->loginRedirect);
                              }
                              else
                              {
                                   $url = Router::url($url);
                              }

                              $this->getModel()->addToAttr('export', $url, 'url');
                         }
                    }
                   
                    die(json_encode($this->getModel()->returnData()));

               }
               else
               {
                    $this->getModel()->Particion->id = $particion_id;
                    $Particion = $this->getModel()->Particion->read();

                    if(!empty($RECAPTCHA_PUBLIC_KEY))
                    {
                         $recaptcha_html = recaptcha_get_html($RECAPTCHA_PUBLIC_KEY, null);
                    }

                    $this->set("recaptcha_html", $recaptcha_html);
                    $this->set("particion_id", $particion_id);
                    $this->set("Particion", $Particion);
               }

          }
          else
          {
               $this->redirect('/');
          }
     }

     public function logout()
     {
	     $this->Session->setFlash('Session Cerrada', 'failure');
	     $this->redirect($this->Auth->logout());
     }
     
     public function beforeFilter()
     {
          $this->Menu->addSubMenu(['padre' => '/usuarios', 'enlace' => '/usuarios/alta/', 'nombre' => 'Altas', 'icono' => 'fa fa-plus']);
          $this->Menu->addSubMenu(['padre' => '/usuarios', 'enlace' => '/usuarios/?searchAuto=1', 'nombre' => 'Buscar', 'icono' => 'fa fa-search']);
          parent::beforeFilter();
	     $this->Auth->allow('logout');
          $this->Auth->allow('login');

          $install = $this->getModel()->Rol->find('all');

          if(sizeof($install) == 0)
          {
               $this->redirect('/roles_permisos/instalar');
          }
     }

}

?>