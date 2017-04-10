<?php

class Particion extends AppModel 
{

     public $name = "Particion";
     public $useTable = "particiones";
     public $displayField = "nombre";
     public $actsAs = array('AutoField');
     public $hasMany = array();

     public function __construct($id = false, $table = NULL, $ds = NULL)
     {

          parent::__construct($id, $table, $ds);
          $this->setAttr('controllerPath', '/particiones/');
          $this->setAttr('controllerUpload', '/archivos/particiones');
          $this->setAttr('controllerRemove', '/particiones/archivoBorrar');
          $this->setAttr('controllerDownload', '/particiones/archivoDescargar');
          $this->setAttr('noSanitize', array());
          $this->setAttr('noApi', array());
          $this->setAttr('controllerTitle', 'Particiones');

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

          $ModelFilter->addFilter("Particion.nombre", null, 'LIKE', array('notEmpty'));

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('Particion.id' => 'Id', 'Particion.nombre' => 'nombre');
          $this->setAttr('orders', $orders);

          $headers = array('#', 'Particion', 'Fecha', 'Acci&oacute;nes');
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
                               <td data-title="Particion">'.$record['Particion']['nombre'].'</td>
                               <td data-title="Fecha">'.FormatComponent::defaultDate($record['Particion']['fecha_alta']).'</td>
                               <td data-title="Acci&oacute;nes">
                                   <a title="Editar" class="btn btn-info btn-sm system-tooltip" href="'.$path.'editar/'.$id.'/0"><i class="fa fa-edit"></i> </a>
                                   <a title="Borrar" class="btn btn-danger btn-sm ajax-delete system-tooltip" data-url="'.$path.'borrar/'.$id.'" data-redirect="'.$path.'" href="#"><i class="fa fa-times"></i> </a>
                                   <label for="check-to-remove-'.$id.'" class="btn btn-warning btn-sm system-tooltip" title="Seleccionar"><input id="check-to-remove-'.$id.'"  style="margin:0px;" type="checkbox" class="inpu-mini remove-list" data-id="'.$id.'" /></label>
                               </td>
                          </tr>';

          }

          return $format;
     }

}


?>
