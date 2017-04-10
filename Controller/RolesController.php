<?php

App::uses('AppController', 'Controller');

class RolesController extends AppController 
{

     public function __construct($request = null, $response = null) 
     {
          $this->setModel('Rol');
          $this->setName("Roles");
          parent::__construct($request, $response);
          $this->startController();
     }

     public function beforeFilter(){
     	$this->Menu->addSubMenu(['padre' => '/roles', 'enlace' => '/roles/alta/', 'nombre' => 'Altas', 'icono' => 'fa fa-plus']);
     	$this->Menu->addSubMenu(['padre' => '/roles', 'enlace' => '/roles/?searchAuto=1', 'nombre' => 'Buscar', 'icono' => 'fa fa-search']);
          $this->Menu->addSubMenu(['padre' => '/roles', 'enlace' => '/roles/verDiagrama', 'nombre' => 'Jerarquia', 'icono' => 'fa fa-tree']);
          parent::beforeFilter();
     }

     public function verDiagrama()
     {
          $modeloPadre = array('belongsTo' => array('Padre' => array('className' => 'RolNivel', 'conditions' => array('Rol.id = Padre.nivel_rol_id', 'Rol.id != Padre.rol_id'), 'foreignKey' => false)));
            $this->getModel()->bindModel($modeloPadre);
            $roles = $this->getModel()->find("all", array('order' => "Rol.id ASC" ));

            $datos = array(); 
            foreach($roles AS $rol)
            {
               $datos[$rol['Rol']['id']] = array('nombre' => $rol['Rol']['nombre'], 'jerarquia' => $this->obtenerPadres($roles, $rol, "<b>".$rol['Rol']['nombre']."</b>"));
            }

            $this->set(compact('datos', 'roles'));

     }

     public function obtenerPadres($roles, $rol, $valor)
     {
          $padre_id = intval(Hash::get($rol, 'Padre.rol_id')); 
          if($padre_id > 0)
          {
                  $filtro = array_filter($roles, function($actual) use ($padre_id){
                    return $actual['Rol']['id'] == $padre_id;
                  });

                  if(sizeof($filtro) > 0){
                       $padre = array_values($filtro)[0];
                  }
                  else{
                       $padre = array();
                  }
                  $valor = $this->obtenerPadres($roles, $padre, Hash::get($padre, "Rol.nombre")."/".$valor);
          }

          return $valor;
     }

}

?>
