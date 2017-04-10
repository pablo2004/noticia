<?php

App::uses('AppController', 'Controller');

class ParticionesController extends AppController 
{

     public function __construct($request = null, $response = null) 
     {
          $this->setModel('Particion');
          $this->setName('Particiones');
          parent::__construct($request, $response);
          $this->startController();
     }

     public function beforeFilter(){
          $this->Menu->addSubMenu(['padre' => '/particiones', 'enlace' => '/particiones/alta/', 'nombre' => 'Altas', 'icono' => 'fa fa-plus']);
          $this->Menu->addSubMenu(['padre' => '/particiones', 'enlace' => '/particiones/?searchAuto=1', 'nombre' => 'Buscar', 'icono' => 'fa fa-search']);
          parent::beforeFilter();
     }

     public function como($id = null)
     {
          $id = intval($id);
          $this->getModel()->id = $id;
          if($this->getModel()->exists())
          {
               $this->Session->write("Auth.User.particion_id", $id);
          }
          exit;
     }

}

?>
