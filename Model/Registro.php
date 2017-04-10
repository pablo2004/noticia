<?php

class Registro extends AppModel 
{

     public $name = "Registro";
     public $useTable = "registros";
     public $displayField = "nombre";
     public $actsAs = array('AutoField', 'Log', 'FilterPartition');
     public $belongsTo = array('Particion');
     public $hasMany = array();

     public function __construct($id = false, $table = NULL, $ds = NULL)
     {
          parent::__construct($id, $table, $ds);
          $this->setAttr('controllerPath', '/registros/');
          $this->setAttr('controllerUpload', '/archivos/registros');
          $this->setAttr('controllerRemove', '/registros/archivoBorrar');
          $this->setAttr('controllerDownload', '/registros/archivoDescargar');
          $this->setAttr('noSanitize', array());
          $this->setAttr('noApi', array());
          $this->setAttr('controllerTitle', 'Registros');

          ////////////////////////////////////////////////////////////////////////
          // FORM VALIDATIONS
          ////////////////////////////////////////////////////////////////////////

          $ErrorManager = new ErrorManager($this->name);

          $ErrorManager->add(new ErrorField('nombre', array('notEmpty')));
          $ErrorManager->add(new ErrorField('telefono', array('notEmpty')));
          $ErrorManager->add(new ErrorField('correo', array('email')));

          $this->setErrorManager($ErrorManager);

          ////////////////////////////////////////////////////////////////////////
          // SEARCH FILTERS
          ////////////////////////////////////////////////////////////////////////
          $ModelFilter = new ModelFilter();

          $ModelFilter->addFilter("Registro.nombre", null, 'LIKE', array('notEmpty'));
          $ModelFilter->addFilter("Registro.correo", null, 'LIKE', array('notEmpty'));

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('Registro.id' => 'Id', 'Registro.nombre' => 'nombre');
          $this->setAttr('orders', $orders);

          $headers = array('#', 'Nombre', 'Correo', 'Tel&eacute;fono', 'Acci&oacute;nes');
           $this->setAttr('tableHeaders', $headers);
     }

     public function getFormatCallback($record)
     {
          $format = '';

          if(is_array($record))
          {
               $path = Router::url($this->getAttr('controllerPath'));
               $upload = Router::url($this->getAttr('controllerUpload'));
               $id = $record[$this->name]['id'];

               $format = '<tr>
                               <td data-title="#">'.$id.'</td>
                               <td>'.$record['Registro']['nombre'].'</td>
                               <td>'.$record['Registro']['correo'].'</td>
                               <td>'.$record['Registro']['telefono'].'</td>
                               <td data-title="Acci&oacute;nes">
                                   <a title="Editar" class="btn btn-info btn-sm system-tooltip" href="'.$path.'editar/'.$id.'"><i class="fa fa-edit"></i> </a>
                                   <a title="Borrar" class="btn btn-danger btn-sm ajax-delete system-tooltip" data-url="'.$path.'borrar/'.$id.'" data-redirect="'.$path.'" href="#"><i class="fa fa-times"></i> </a>
                                   <label for="check-to-remove-'.$id.'" class="btn btn-warning btn-sm system-tooltip" title="Seleccionar"><input id="check-to-remove-'.$id.'" style="margin:0px;" type="checkbox" class="inpu-mini remove-list" data-id="'.$id.'" /></label>
                               </td>
                          </tr>';
          }

          return $format;
     }


}


?>
