<?php

App::uses('AppController', 'Controller');

class CatalogosController extends AppController 
{

     public function __construct($request = null, $response = null) 
     {
          $this->setModel("Catalogo");
          $this->setName("Catalogos");
          parent::__construct($request, $response);
          $this->startController();
     }

     public function beforeFilter(){
          $this->Menu->addSubMenu(['padre' => '/catalogos', 'enlace' => '/catalogos/alta/', 'nombre' => 'Altas', 'icono' => 'fa fa-plus']);
          $this->Menu->addSubMenu(['padre' => '/catalogos', 'enlace' => '/catalogos/?searchAuto=1', 'nombre' => 'Buscar', 'icono' => 'fa fa-search']);
          parent::beforeFilter();
          $this->Auth->allow('apiCatalogo');
     }

     public function beforeRender()
     {
          parent::beforeRender();
          $padres = $this->getModel()->find('list', ['group' => array('Catalogo.nombre'), 'conditions' => array('Catalogo.activo =' => 1)]);
          $padres[0] = "NINGUNO";
          ksort($padres);
          $this->set("padres", $padres);
     }

     public function apiCatalogo($catalogo){
          $this->api();
          $catalogo = trim($catalogo);
          $this->catalog("valor", "etiqueta", "Catalogo.nombre=".$catalogo);
     }

}

?>
