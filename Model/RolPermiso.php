<?php

class RolPermiso extends AppModel 
{
     public $name = "RolPermiso";
     public $useTable = "rol_permisos";
     public $displayField = "permiso";
     public $actsAs = array('AutoField');
     public $hasMany = array();
     public $belongsTo = array('Rol');

     public function __construct($id = false, $table = NULL, $ds = NULL)
     {
          parent::__construct($id, $table, $ds);
          $this->setAttr('controllerPath', '/roles_permisos/');
          $this->setAttr('controllerUpload', '/archivos/roles_permisos');
          $this->setAttr('controllerRemove', '/roles_permisos/archivoBorrar');
          $this->setAttr('controllerDownload', '/roles_permisos/archivoDescargar');
          $this->setAttr('noSanitize', array());
          $this->setAttr('noApi', array());
          $this->setAttr('controllerTitle', 'Roles Permisos');

          ////////////////////////////////////////////////////////////////////////
          // FORM VALIDATIONS
          ////////////////////////////////////////////////////////////////////////

          $ErrorManager = new ErrorManager($this->name);

          //$ErrorManager->add(new ErrorField('rol_id', array('numeric')));
          $ErrorManager->add(new ErrorField('controlador', array('notEmpty')));
          $ErrorManager->add(new ErrorField('controlador', array('maxLength', 100)));
          $ErrorManager->add(new ErrorField('accion', array('notEmpty')));
          $ErrorManager->add(new ErrorField('accion', array('maxLength', 100)));
          $ErrorManager->add(new ErrorField('permitir', array('inList', array('0', '1'))));

          $this->setErrorManager($ErrorManager);

          ////////////////////////////////////////////////////////////////////////
          // SEARCH FILTERS
          ////////////////////////////////////////////////////////////////////////
          $ModelFilter = new ModelFilter();

          $ModelFilter->addFilter("RolPermiso.controlador", null, '=', array('notEmpty'));

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('RolPermiso.id' => 'Id', 'RolPermiso.controlador' => 'area');
          $this->setAttr('orders', $orders);

          $headers = array('#', 'Permiso', 'Permitido', 'Acci&oacute;nes');
          $this->setAttr('tableHeaders', $headers);
     }

     public function getFormatCallback($record)
     {
          $format = '';

          if(is_array($record))
          {
               $permitido = intval($record['RolPermiso']['permitir']);
               $permitido_label = "No";
               $permitido_class = "danger";

               if($permitido == 1)
               {
                    $permitido_label = "Si";
                    $permitido_class = "success";
               }

               $path = Router::url($this->getAttr('controllerPath'));
               $id = $record[$this->name]['id'];
               $form_url = $path."editar/".$id;
               
               $format = '<tr data-id="'.$id.'" class="'.$permitido_class.'">
                               <td data-title="#">'.$id.'</td>
                               <td data-title="Permiso">'.$record['RolPermiso']['controlador'].': '.$record['RolPermiso']['accion'].'</td>
                               <td data-title="Permitido"><b>'.$permitido_label.'</b></td>
                               <td data-title="Acci&oacute;nes">
                                    <button data-width="600" type="button" title="Editar" class="btn btn-info btn-sm ajax-update system-tooltip" data-id="'.$id.'" data-success="on'.$this->name.'Update" data-model="'.$this->name.'" data-form-url="'.$form_url.'"><i class="fa fa-edit"></i></button>
                                    <a data-title="Borrar" class="btn btn-danger btn-sm system-tooltip ajax-delete" href="#" data-url="'.$path.'borrar/'.$id.'" data-redirect=""><i class="fa fa-times"></i></a>
                                    <label for="check-to-remove-'.$this->alias.'-'.$id.'" class="btn btn-warning btn-sm" title="Seleccionar"><input id="check-to-remove-'.$this->alias.'-'.$id.'" style="margin:0px;" type="checkbox" class="inpu-mini check-item" data-id="'.$id.'" /></label>
                               </td>
                          </tr>';

          }

          return $format;
     }
}

?>
