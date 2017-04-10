<?php

App::uses('AppController', 'Controller');

class ImagenesController extends AppController 
{

     public function __construct($request = null, $response = null) 
     {
          $this->setModel('Imagen');
          $this->setName("Imagenes");
          parent::__construct($request, $response);
          $this->startController();
     }


     public function beforeFilter()
     {
          $this->Menu->addSubMenu(['padre' => '/imagenes', 'enlace' => '/imagenes/alta/', 'nombre' => 'Altas', 'icono' => 'fa fa-plus']);
          $this->Menu->addSubMenu(['padre' => '/imagenes', 'enlace' => '/imagenes/?searchAuto=1', 'nombre' => 'Buscar', 'icono' => 'fa fa-search']);
          parent::beforeFilter();
          $this->Auth->allow('apiAlta');
     }

     public function apiAlta()
     {
          $this->api();
          $data = array();
          $data['particion_id'] = intval(Hash::get($_GET, 'particion_id'));
          $data['usuario_id'] = intval(Hash::get($_POST, 'usuario_id'));
          $data['pid'] = intval(Hash::get($_POST, 'pid'));
          $data['tipo_id'] = intval(Hash::get($_POST, 'tipo_id'));
          $data['archivo'] = uniqid().".jpg";
          $data['descripcion'] = trim(Hash::get($_POST, 'descripcion'));

          $imagen = trim(Hash::get($_POST, 'imagen'));
          $imagen = str_replace(" ", "+", $imagen);
          $ruta = APP.WEBROOT_DIR.$this->getModel()->getAttr("controllerUpload")."/";

          file_put_contents($ruta.$data['archivo'], base64_decode($imagen));
          chmod($ruta.$data['archivo'], 0777);

          $this->getModel()->create();
          $this->getModel()->save(array('Imagen' => $data), false);

          echo '{"result": 1}';
          exit;
     }

}

?>