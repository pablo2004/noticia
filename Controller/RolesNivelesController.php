<?php

App::uses('AppController', 'Controller');

class RolesNivelesController extends AppController
{     
     public function __construct($request = null, $response = null) 
     {
          $this->setModel('RolNivel');
          $this->setName("RolesNiveles");
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
          $roles = $this->getModel()->Rol->find('list');
          $this->set("roles", $roles);
     }
}

?>
