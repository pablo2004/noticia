<?php

App::uses('AppController', 'Controller');

class VideosController extends AppController 
{

     public function __construct($request = null, $response = null) 
     {
          $this->setModel('Video');
          $this->setName('Videos');
          parent::__construct($request, $response);
          $this->startController();
     }


     public function beforeFilter()
     {
          $this->Menu->addSubMenu(['padre' => '/videos', 'enlace' => '/videos/alta/', 'nombre' => 'Altas', 'icono' => 'fa fa-plus']);
          $this->Menu->addSubMenu(['padre' => '/videos', 'enlace' => '/videos/?searchAuto=1', 'nombre' => 'Buscar', 'icono' => 'fa fa-search']);

          $this->Auth->allow("apiUpload");
          parent::beforeFilter();
     }

     public function apiUpload(){

          $this->api();
          $particion_id = intval(Hash::get($_GET, 'particion_id'));
          $registro_id = intval(Hash::get($_GET, 'registro_id'));
          $resultado = ['result' => 0];

          App::uses('UploadComponent', 'Controller/Component');
          $upload = new UploadComponent();

          $uploadPath = WWW_ROOT . $this->getModel()->getAttr('controllerUpload');
          $name = uniqid();

          $upload->setPath($uploadPath);
          $upload->setName($name);
          $upload->setFile("file");
          $upload->setSupportedExtensions(['mp4']);

          if($upload->uploadFile()){
               $file = $name.".mp4";

               $save = ['particion_id' => $particion_id, 'registro_id' => $registro_id, 'archivo' => $file];
               $this->getModel()->create();
               $this->getModel()->save(['Video' => $save], false);
               $resultado['result'] = $uploadPath;
          }

          echo json_encode($resultado);
          exit;
     }

}

?>