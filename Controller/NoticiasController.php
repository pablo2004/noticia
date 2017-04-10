<?php

App::uses('AppController', 'Controller');

class NoticiasController extends AppController 
{

     public function __construct($request = null, $response = null) 
     {
          $this->setModel('Noticia');
          $this->setName('Noticias');
          parent::__construct($request, $response);
          $this->startController();
     }


     public function beforeFilter()
     {
          $this->Menu->addSubMenu(['padre' => '/noticias', 'enlace' => '/noticias/alta/', 'nombre' => 'Altas', 'icono' => 'fa fa-plus']);
          $this->Menu->addSubMenu(['padre' => '/noticias', 'enlace' => '/noticias/?searchAuto=1', 'nombre' => 'Buscar', 'icono' => 'fa fa-search']);

          $this->Auth->allow("apiNoticias");
          parent::beforeFilter();
     }

     public function apiNoticias($limit = 10){

          $this->api();
          $particion_id = intval(Hash::get($_GET, 'particion_id'));
          $limit = intval($limit);

          $noticias = $this->getModel()->find("all", ["recursive" => -1, 'limit' => $limit, 'order' => 'Noticia.id DESC', 'conditions' => ['Noticia.particion_id =' => $particion_id, 'Noticia.activa =' => 1]]);

          $noticias = Hash::extract($noticias, "{n}.Noticia");

          echo json_encode(["noticias" => $noticias]);
          exit;
     }

}

?>