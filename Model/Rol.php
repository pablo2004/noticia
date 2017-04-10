<?php

class Rol extends AppModel 
{
     public $name = "Rol";
     public $useTable = "roles";
     public $displayField = "nombre";
     public $actsAs = array('AutoField', 'Log');
     public $belongsTo = array();

     public function __construct($id = false, $table = NULL, $ds = NULL)
     {
          parent::__construct($id, $table, $ds);
          $this->setAttr('controllerPath', '/roles/');
          $this->setAttr('controllerUpload', '/archivos/roles');
          $this->setAttr('controllerRemove', '/roles/archivoBorrar');
          $this->setAttr('controllerDownload', '/roles/archivoDescargar');
          $this->setAttr('noSanitize', array());
          $this->setAttr('noApi', array());
          $this->setAttr('controllerTitle', 'Roles');

          ////////////////////////////////////////////////////////////////////////
          // FORM VALIDATIONS
          ////////////////////////////////////////////////////////////////////////

          $ErrorManager = new ErrorManager($this->name);

          $ErrorManager->add(new ErrorField('nombre', array('notEmpty')));
          $ErrorManager->add(new ErrorField('nombre', array('maxLength', 100)));

          $this->setErrorManager($ErrorManager);

          ////////////////////////////////////////////////////////////////////////
          // SEARCH FILTERS
          ////////////////////////////////////////////////////////////////////////
          $ModelFilter = new ModelFilter();

          $ModelFilter->addFilter("Rol.nombre", null, 'LIKE', array('notEmpty'));

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('Rol.id' => 'Id', 'Rol.nombre' => 'nombre');
          $this->setAttr('orders', $orders);

          $headers = array('#', 'Nombre', 'Fecha Alta', 'Acci&oacute;nes');
          $this->setAttr('tableHeaders', $headers);
     }

     public function getFormatCallback($record)
     {
          $format = '';

          if(is_array($record))
          {
               $path = Router::url($this->getAttr('controllerPath'));
               $id = $record[$this->name]['id'];

               $format = '<tr>
                               <td data-title="#">'.$id.'</td>
                               <td data-title="Rol"><a data-pk="'.$id.'" id="rol_nombre_'.$id.'" data-type="text" data-url="'.$path.'campo/'.$id.'/nombre" href="#" class="xeditable">'.$record['Rol']['nombre'].'</a></td>
                               <td data-title="Fecha">'.FormatComponent::defaultDate($record['Rol']['fecha_alta']).'</td>
                               <td data-title="Acci&oacute;nes">

                                    <a title="Editar" class="btn btn-info btn-sm system-tooltip" href="'.$path.'editar/'.$id.'"><i class="fa fa-pencil"></i> </a>
                                    <a title="Borrar" class="btn btn-danger btn-sm ajax-delete system-tooltip" data-url="'.$path.'borrar/'.$id.'" data-redirect="'.$path.'" href="#"><i class="fa fa-times"></i> </a>
                                    <label for="check-to-remove-'.$id.'" class="btn btn-warning btn-sm system-tooltip" title="Seleccionar">
                                          <input id="check-to-remove-'.$id.'" type="checkbox" class="inpu-mini remove-list" data-id="'.$id.'" />
                                    </label>

                               </td>
                          </tr>';
          }

          return $format;
     }


}


?>
