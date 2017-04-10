<?php

class Catalogo extends AppModel 
{
     public $name = "Catalogo";
     public $useTable = "catalogos";
     public $displayField = "nombre";
     public $actsAs = array('AutoField', 'Log');
     public $belongsTo = array();

     public function __construct($id = false, $table = NULL, $ds = NULL)
     {
          parent::__construct($id, $table, $ds);
          $this->setAttr('controllerPath', '/catalogos/');
          $this->setAttr('controllerUpload', '/archivos/catalogos');
          $this->setAttr('controllerRemove', '/catalogos/archivoBorrar');
          $this->setAttr('controllerDownload', '/catalogos/archivoDescargar');
          $this->setAttr('noSanitize', array());
          $this->setAttr('noApi', array());
          $this->setAttr('controllerTitle', 'Catalogos');

          ////////////////////////////////////////////////////////////////////////
          // FORM VALIDATIONS
          ////////////////////////////////////////////////////////////////////////

          $ErrorManager = new ErrorManager($this->name);

          $ErrorManager->add(new ErrorField('nombre', array('notEmpty')));
          $ErrorManager->add(new ErrorField('nombre', array('maxLength', 50)));
          $ErrorManager->add(new ErrorField('etiqueta', array('notEmpty')));
          $ErrorManager->add(new ErrorField('etiqueta', array('maxLength', 50)));
          $ErrorManager->add(new ErrorField('valor', array('notEmpty')));
          $ErrorManager->add(new ErrorField('valor', array('maxLength', 200)));

          $this->setErrorManager($ErrorManager);

          ////////////////////////////////////////////////////////////////////////
          // SEARCH FILTERS
          ////////////////////////////////////////////////////////////////////////
          $ModelFilter = new ModelFilter();

          $ModelFilter->addFilter("Catalogo.nombre", null, 'LIKE', array('notEmpty'));
          $ModelFilter->addFilter("Catalogo.etiqueta", null, 'LIKE', array('notEmpty'));
          $ModelFilter->addFilter("Catalogo.valor", null, 'LIKE', array('notEmpty'));

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('Catalogo.id' => 'Id', 'Catalogo.nombre' => 'catalogo', 'Catalogo.valor' => 'valor', 'Catalogo.etiqueta' => 'etiqueta');
          $this->setAttr('orders', $orders);

          $headers = array('#', 'Nombre', 'Etiqueta', 'Valor', 'Acci&oacute;nes');
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
                               <td data-title="Nombre"><a data-pk="'.$id.'" id="catalogo_nombre_'.$id.'" data-type="text" data-url="'.$path.'campo/'.$id.'/nombre" href="#" class="xeditable">'.$record['Catalogo']['nombre'].'</a></td>
                               <td data-title="Nombre"><a data-pk="'.$id.'" id="catalogo_etiqueta_'.$id.'" data-type="text" data-url="'.$path.'campo/'.$id.'/etiqueta" href="#" class="xeditable">'.$record['Catalogo']['etiqueta'].'</a></td>
                               <td data-title="Nombre"><a data-pk="'.$id.'" id="catalogo_valor_'.$id.'" data-type="text" data-url="'.$path.'campo/'.$id.'/valor" href="#" class="xeditable">'.$record['Catalogo']['valor'].'</a></td>
                               <td data-title="Acci&oacute;nes">
                                   <a title="Editar" class="btn btn-info btn-sm system-tooltip" href="'.$path.'editar/'.$id.'"><i class="fa fa-pencil"></i> </a>
                                   <a title="Borrar" class="btn btn-danger btn-sm ajax-delete system-tooltip" data-url="'.$path.'borrar/'.$id.'" data-redirect="'.$path.'" href="#"><i class="fa fa-times"></i> </a>
                                   <label for="check-to-remove-'.$id.'" class="btn btn-warning btn-sm system-tooltip" title="Seleccionar"><input id="check-to-remove-'.$id.'" style="margin:0px;" type="checkbox" class="inpu-mini remove-list" data-id="'.$id.'" /></label>
                               </td>
                          </tr>';
          }

          return $format;
     }


}


?>
