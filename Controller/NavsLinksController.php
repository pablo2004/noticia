<?php

App::uses('AppController', 'Controller');

class NavsLinksController extends AppController
{     
     public function __construct($request = null, $response = null) 
     {
          $this->setModel('NavLink');
          $this->setName('NavsLinks');
          parent::__construct($request, $response);
          $this->startController();
     }

     public function inserta($id, $field = "padre_id", $return = false)
     {    
          if($this->request->is('post'))
          {
               $this->request->data['NavLink']['rol_permitido'] = implode(",", $this->request->data['NavLink']['rol_permitido']);
          }
          parent::inserta($id, $field, $return);
     }

     public function editar($id = 0, $cache = 1, $return = false)
     {

          if(isset($this->request->data['NavLink']['rol_permitido']))
          {
               $this->request->data['NavLink']['rol_permitido'] = implode(",", $this->request->data['NavLink']['rol_permitido']);
          }

          parent::editar($id, $cache, $return);
     }


     public function index($id = null, $limit = 10)
     {
          parent::lista($id, "padre_id", $limit);
     }

     public function beforeRender()
     {
          parent::beforeRender();
          $Rol = AppController::_newInstance("Rol");
          $roles = $Rol->find('list');
          $roles[-2] = "- TODOS";
          $roles[-1] = "- SOLO USUARIOS";
          $roles[0] = "- NO USUARIOS";
          ksort($roles);
          $this->set("roles", $roles);
     }
}

?>
