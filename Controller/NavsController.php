<?php

App::uses('AppController', 'Controller');

class NavsController extends AppController 
{

     public function __construct($request = null, $response = null) 
     {
          $this->setModel('Nav');
          $this->setName('Navs');
          parent::__construct($request, $response);
          $this->startController();
     }

     public function beforeFilter(){
          $this->Menu->addSubMenu(['padre' => '/navs', 'enlace' => '/navs/alta/', 'nombre' => 'Altas', 'icono' => 'fa fa-plus']);
          $this->Menu->addSubMenu(['padre' => '/navs', 'enlace' => '/navs/?searchAuto=1', 'nombre' => 'Buscar', 'icono' => 'fa fa-search']);
          parent::beforeFilter();
     }

     public function alta($return = false)
     {
          if($this->request->is('post'))
          {
               $this->request->data['Nav']['rol_permitido'] = implode(",", $this->request->data['Nav']['rol_permitido']);
          }
          parent::alta($return);
     }

     public function editar($id = 0, $cache = 1, $return = false)
     {
          if(isset($this->request->data['Nav']['rol_permitido']))
          {
               $this->request->data['Nav']['rol_permitido'] = implode(",", $this->request->data['Nav']['rol_permitido']);
          }
          parent::editar($id, $cache);
     }

     public function reporte_basico()
     {
          $campos = [
               'lado' => 'LadoNavegacion.etiqueta',
               'nombre' => 'Nav.nombre',
               'enlace' => 'Nav.enlace'
          ];
       
          $ModelFilter = new ModelFilter();
          
          $ModelFilter->addFilter("Nav.lado_menu", null, '=', array('comparison', array('>', 0)));

          $this->reporte($this->getModel(), 'reporteBasico', $campos, [], $ModelFilter);
     }

     public function beforeRender()
     {
          parent::beforeRender();
          $Rol = AppController::_newInstance("Rol");
          $roles = $Rol->find('list');
          $navs = $this->getModel()->find('list');
          $roles[-2] = "- TODOS";
          $roles[-1] = "- SOLO USUARIOS";
          $roles[0] = "- NO USUARIOS";
          $navs[0] = 'Ninguno';
          ksort($roles);
          ksort($navs);
          $this->set("roles", $roles);
          $this->set("navs", $navs);
     }

}

?>
