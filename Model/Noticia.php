<?php

class Noticia extends AppModel 
{

     public $name = "Noticia";
     public $useTable = "noticias";
     public $displayField = "titulo";
     public $actsAs = array('AutoField', 'Log', 'FilterPartition');
     public $belongsTo = array('Particion');
     public $hasMany = array();

     public function __construct($id = false, $table = NULL, $ds = NULL)
     {
          parent::__construct($id, $table, $ds);
          $this->setAttr('controllerPath', '/noticias/');
          $this->setAttr('controllerUpload', '/archivos/noticias');
          $this->setAttr('controllerRemove', '/noticias/archivoBorrar');
          $this->setAttr('controllerDownload', '/noticias/archivoDescargar');
          $this->setAttr('noSanitize', array('Noticia.noticia'));
          $this->setAttr('noApi', array());
          $this->setAttr('controllerTitle', 'Noticias');

          ////////////////////////////////////////////////////////////////////////
          // FORM VALIDATIONS
          ////////////////////////////////////////////////////////////////////////

          $ErrorManager = new ErrorManager($this->name);

          $ErrorManager->add(new ErrorField('titulo', array('notEmpty')));
          $ErrorManager->add(new ErrorField('noticia', array('notEmpty')));
          $ErrorManager->add(new ErrorField('extracto', array('notEmpty')));

          $this->setErrorManager($ErrorManager);

          ////////////////////////////////////////////////////////////////////////
          // SEARCH FILTERS
          ////////////////////////////////////////////////////////////////////////
          $ModelFilter = new ModelFilter();

          $ModelFilter->addFilter("Noticia.titulo", null, 'LIKE', array('notEmpty'));
          $ModelFilter->addFilter("Noticia.extracto", null, 'LIKE', array('notEmpty'));

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('Noticia.id' => 'Id', 'Noticia.titulo' => 'titulo');
          $this->setAttr('orders', $orders);

          $headers = array('#', 'Titulo', 'Extracto', 'Fecha', 'Acci&oacute;nes');
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
                               <td>'.$record['Noticia']['titulo'].'</td>
                               <td>'.$record['Noticia']['extracto'].'</td>
                               <td>'.FormatComponent::defaultDate($record['Noticia']['fecha_alta']).'</td>
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
