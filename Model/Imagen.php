<?php

class Imagen extends AppModel 
{

     public $name = "Imagen";
     public $useTable = "imagenes";
     public $displayField = "archivo";
     public $actsAs = array('AutoField', 'Log', 'FilterPartition');
     public $belongsTo = array('Particion');
     public $hasMany = array();

     public function __construct($id = false, $table = NULL, $ds = NULL)
     {
          parent::__construct($id, $table, $ds);
          $this->setAttr('controllerPath', '/imagenes/');
          $this->setAttr('controllerUpload', '/archivos/imagenes');
          $this->setAttr('controllerRemove', '/imagenes/archivoBorrar');
          $this->setAttr('controllerDownload', '/imagenes/archivoDescargar');
          $this->setAttr('noSanitize', array());
          $this->setAttr('noApi', array());
          $this->setAttr('controllerTitle', 'Imagenes');

          ////////////////////////////////////////////////////////////////////////
          // FORM VALIDATIONS
          ////////////////////////////////////////////////////////////////////////

          $ErrorManager = new ErrorManager($this->name);

          $ErrorManager->add(new ErrorField('archivo', array('notEmpty')));

          $this->setErrorManager($ErrorManager);

          ////////////////////////////////////////////////////////////////////////
          // SEARCH FILTERS
          ////////////////////////////////////////////////////////////////////////
          $ModelFilter = new ModelFilter();

          $ModelFilter->addFilter("Imagen.descripcion", null, 'LIKE', array('notEmpty'));

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('Imagen.id' => 'Id', 'Imagen.pid' => 'Pid');
          $this->setAttr('orders', $orders);

          $headers = array('#', 'Imagen', 'Acci&oacute;nes');
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
                               <td><img style="width:100px;height:100px;" alt="'.$id.'" title="'.$id.'" src="'.$upload.'/'.$record['Imagen']['archivo'].'" /></td>
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
