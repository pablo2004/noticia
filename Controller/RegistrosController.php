<?php

App::uses('AppController', 'Controller');

class RegistrosController extends AppController 
{

     public function __construct($request = null, $response = null) 
     {
          $this->setModel('Registro');
          $this->setName('Registros');
          parent::__construct($request, $response);
          $this->startController();
     }

     public function beforeFilter()
     {
          $this->Menu->addSubMenu(['padre' => '/registros', 'enlace' => '/registros/alta/', 'nombre' => 'Altas', 'icono' => 'fa fa-plus']);
          $this->Menu->addSubMenu(['padre' => '/registros', 'enlace' => '/registros/?searchAuto=1', 'nombre' => 'Buscar', 'icono' => 'fa fa-search']);

          $this->Auth->allow('apiAlta');
          $this->Auth->allow('apiAcceso');
          parent::beforeFilter();
     }

     public function apiAlta(){
          $this->api();

          $particion_id = intval(Hash::get($_GET, 'particion_id'));
          $datos = $_POST;
          $datos['particion_id'] = $particion_id;

          $this->getModel()->create();
          $this->getModel()->save(['Registro' => $datos], false);
          $id = $this->getModel()->lastId();
          $this->getModel()->id = $id;
          $user = json_encode($this->getModel()->read());

          echo '{"id": '.$id.', "user": '.$user.'}';
          exit;
     }


     public function apiAcceso(){
          $this->api();

          $particion_id = intval(Hash::get($_GET, 'particion_id'));
          $correo = trim(Hash::get($_POST, 'correo'));
          $password = trim(Hash::get($_POST, 'password'));

          $user = $this->getModel()->findByCorreoAndPasswordAndParticionId($correo, $password, $particion_id);
          $result = sizeof($user);

          echo '{"result": '.$result.', "user": '.json_encode($user).'}';
          exit;
     }

}

?>