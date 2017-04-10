<?php

App::uses('AppController', 'Controller');

class ComentariosController extends AppController 
{

     public function __construct($request = null, $response = null) 
     {
          $this->setModel('Comentario');
          $this->setName('Comentarios');
          parent::__construct($request, $response);
          $this->startController();
     }


     public function beforeFilter()
     {
          $this->Menu->addSubMenu(['padre' => '/comentarios', 'enlace' => '/comentarios/alta/', 'nombre' => 'Altas', 'icono' => 'fa fa-plus']);
          $this->Menu->addSubMenu(['padre' => '/comentarios', 'enlace' => '/comentarios/?searchAuto=1', 'nombre' => 'Buscar', 'icono' => 'fa fa-search']);

          $this->Auth->allow("apiComentarios");
          $this->Auth->allow("apiAlta");
          parent::beforeFilter();
     }

     public function apiComentarios($limit = 20){

          $this->api();
          $particion_id = intval(Hash::get($_GET, 'particion_id'));
          $noticia_id = intval(Hash::get($_GET, 'noticia_id'));
          $limit = intval($limit);

          $noticias = $this->getModel()->find("all", ['limit' => $limit, 'order' => 'Comentario.id DESC', 'conditions' => ['Comentario.particion_id =' => $particion_id, 'Comentario.validado =' => 1, 'Comentario.noticia_id =' => $noticia_id]]);

          echo json_encode($noticias);
          exit;
     }

     public function apiAlta(){
          $this->api();

          $particion_id = intval(Hash::get($_GET, 'particion_id'));
          $datos = $_POST;
          $datos['particion_id'] = $particion_id;

          $this->getModel()->create();
          $this->getModel()->save(['Comentario' => $datos], false);
          $id = $this->getModel()->lastId();

          echo '{"id": '.$id.'}';
          exit;
     }


}

?>