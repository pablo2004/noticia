<?php

class RolNivel extends AppModel 
{
     public $name = "RolNivel";
     public $useTable = "rol_niveles";
     public $displayField = "";
     public $actsAs = array('AutoField');
     public $hasMany = array();
     public $belongsTo = array("Rol", 'Nivel' => array('className' => 'Rol', 'foreignKey' => 'nivel_rol_id'));

     public function __construct($id = false, $table = NULL, $ds = NULL)
     {
          parent::__construct($id, $table, $ds);
          $this->setAttr('controllerPath', '/roles_niveles/');
          $this->setAttr('controllerUpload', '/archivos/roles_niveles');
          $this->setAttr('controllerRemove', '/roles_niveles/archivoBorrar');
          $this->setAttr('controllerDownload', '/roles_niveles/archivoDescargar');
          $this->setAttr('noSanitize', array());
          $this->setAttr('noApi', array());
          $this->setAttr('controllerTitle', 'Roles Niveles');

          ////////////////////////////////////////////////////////////////////////
          // FORM VALIDATIONS
          ////////////////////////////////////////////////////////////////////////

          $ErrorManager = new ErrorManager($this->name);

          //$ErrorManager->add(new ErrorField('rol_id', array('numeric')));
          $ErrorManager->add(new ErrorField('nivel_rol_id', array('numeric')));

          $this->setErrorManager($ErrorManager);

          ////////////////////////////////////////////////////////////////////////
          // SEARCH FILTERS
          ////////////////////////////////////////////////////////////////////////
          $ModelFilter = new ModelFilter();

          //$ModelFilter->addFilter("RolNivel.rol_id", null, '=', array('comparison', array('>', 0)));
          $ModelFilter->addFilter("RolNivel.nivel_rol_id", null, '=', array('comparison', array('>', 0)));

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('RolNivel.id' => 'Id', 'RolNivel.nombre' => 'nivel');
          $this->setAttr('orders', $orders);

          $headers = array('#', 'Actua Sobre', 'Fecha', 'Acci&oacute;nes');
          $this->setAttr('tableHeaders', $headers);
     }

     public function getFormatCallback($record)
     {
          $format = '';

          if(is_array($record))
          {
               $path = Router::url($this->getAttr('controllerPath'));
               $id = $record[$this->name]['id'];
               $form_url = $path."editar/".$id;

               $format = '<tr data-id="'.$id.'">
                               <td data-title="#">'.$id.'</td>
                               <td data-title="Actua Sobre">'.$record['Nivel']['nombre'].'</td>
                               <td data-title="Fecha">'.$record['RolNivel']['fecha_alta'].'</td>
                               <td data-title="Acci&oacute;nes">
                                    <button data-width="600" type="button" title="Editar" class="btn btn-info btn-sm system-tooltip ajax-update" data-id="'.$id.'" data-success="on'.$this->name.'Update" data-model="'.$this->name.'" data-form-url="'.$form_url.'"><i class="fa fa-edit"></i></button>
                                    <a data-title="Borrar" class="btn btn-danger btn-sm system-tooltip ajax-delete" href="#" data-url="'.$path.'borrar/'.$id.'" data-redirect=""><i class="fa fa-times"></i></a>
                                    <label for="check-to-remove-'.$this->alias.'-'.$id.'" class="btn btn-warning btn-sm" title="Seleccionar"><input id="check-to-remove-'.$this->alias.'-'.$id.'" style="margin:0px;" type="checkbox" class="inpu-mini check-item" data-id="'.$id.'" /></label>
                               </td>
                          </tr>';

          }

          return $format;
     }

     public function isParent($rol_parent, $rol_child, $found = array(), $levels = array())
     {
          $return = false;
          $rol_parent = intval($rol_parent);
          $rol_child = intval($rol_child);
          $rol_parents = array();
          $levels = (sizeof($levels) == 0) ? $this->find('all') : $levels;

          if($rol_parent == $rol_child)
          {
               $find = Hash::extract($levels, '{n}.RolNivel[rol_id='.$rol_parent.'][nivel_rol_id='.$rol_child.']');

               if(sizeof($find) >= 1)
               {
                    $return = true;
               }
          }
          else
          {
               $rol_parents = Hash::extract($levels, '{n}.RolNivel[rol_id='.$rol_parent.']');
          }

          if(!$return)
          {
               foreach($rol_parents AS $rol)
               {
                    if($rol['nivel_rol_id'] == $rol_child)
                    {
                         $return = true;
                         break;
                    }
               }
          }

          if(!$return)
          {
               foreach($rol_parents AS $rol)
               {
                    if(!in_array($rol['nivel_rol_id'], $found))
                    { 
                         array_push($found, $rol['nivel_rol_id']);
                         $return = $this->isParent($rol['nivel_rol_id'], $rol_child, $found, $levels);
                         if($return)
                         {
                              break;
                         }
                    }
               }
          }
          
          return $return;
     }

     public function isRolUserParent($rol_parent, $user_id)
     { 
          $return = false;
          $rol_parent = intval($rol_parent);
          $user_id = intval($user_id);
          App::uses("Usuario", "Model");
          $Usuario = new Usuario();
          $Usuario->id = $user_id;

          if($Usuario->exists())
          {
               $Usuario = $Usuario->read();
               $return = $this->isParent($rol_parent, $Usuario['Usuario']['rol_id']);
          }
          
          return $return;
     }
}

?>
