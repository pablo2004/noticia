<?php

class Comentario extends AppModel 
{

     public $name = "Comentario";
     public $useTable = "comentarios";
     public $displayField = "comentario";
     public $actsAs = array('AutoField', 'Log', 'FilterPartition');
     public $belongsTo = array('Particion', 'Noticia', 'Registro');
     public $hasMany = array();

     public function __construct($id = false, $table = NULL, $ds = NULL)
     {
          parent::__construct($id, $table, $ds);
          $this->setAttr('controllerPath', '/comentarios/');
          $this->setAttr('controllerUpload', '/archivos/comentarios');
          $this->setAttr('controllerRemove', '/comentarios/archivoBorrar');
          $this->setAttr('controllerDownload', '/comentarios/archivoDescargar');
          $this->setAttr('noSanitize', array());
          $this->setAttr('noApi', array());
          $this->setAttr('controllerTitle', 'Comentarios');

          ////////////////////////////////////////////////////////////////////////
          // FORM VALIDATIONS
          ////////////////////////////////////////////////////////////////////////

          $ErrorManager = new ErrorManager($this->name);

          $ErrorManager->add(new ErrorField('comentario', array('notEmpty')));
          $ErrorManager->add(new ErrorField('noticia_id', array('numeric')));
          $ErrorManager->add(new ErrorField('registro_id', array('numeric')));

          $this->setErrorManager($ErrorManager);

          ////////////////////////////////////////////////////////////////////////
          // SEARCH FILTERS
          ////////////////////////////////////////////////////////////////////////
          $ModelFilter = new ModelFilter();

          $ModelFilter->addFilter("Comentario.comentario", null, 'LIKE', array('notEmpty'));

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('Comentario.id' => 'Id');
          $this->setAttr('orders', $orders);

          $headers = array('#', 'Noticia', 'Usuario', 'Mensaje', 'Validar', 'Acci&oacute;nes');
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

               if($record['Comentario']['validado'] == 0){
                    $checked = '';
                    $class = 'btn-danger';
               }
               else{
                    $checked = 'checked="true"';
                    $class = 'btn-success';
               }

               $format = '<tr>
                               <td data-title="#">'.$id.'</td>
                               <td>'.$record['Noticia']['titulo'].'</td>
                               <td>'.$record['Registro']['nombre'].'</td>
                               <td>'.$record['Comentario']['comentario'].'</td>
                               <td><label for="checkbox-'.$id.'" class="btn '.$class.' btn-sm"><input '.$checked.' id="checkbox-'.$id.'" style="margin:0px;" type="checkbox" class="valida-on" data-id="'.$id.'" /></label></td>
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
