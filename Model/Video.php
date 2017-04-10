<?php

class Video extends AppModel 
{

     public $name = "Video";
     public $useTable = "videos";
     public $displayField = "archivo";
     public $actsAs = array('AutoField', 'Log', 'FilterPartition');
     public $belongsTo = array('Particion', 'Registro');
     public $hasMany = array();

     public function __construct($id = false, $table = NULL, $ds = NULL)
     {
          parent::__construct($id, $table, $ds);
          $this->setAttr('controllerPath', '/videos/');
          $this->setAttr('controllerUpload', '/archivos/clips');
          $this->setAttr('controllerRemove', '/videos/archivoBorrar');
          $this->setAttr('controllerDownload', '/videos/archivoDescargar');
          $this->setAttr('noSanitize', array());
          $this->setAttr('noApi', array());
          $this->setAttr('controllerTitle', 'Videos');

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

          $ModelFilter->addFilter("Video.registro_id", null, 'LIKE', array('comparison', ['>', 0]));

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('Video.id' => 'Id', 'Video.registro_id' => 'usuario');
          $this->setAttr('orders', $orders);

          $headers = array('#', 'Usuario', 'Video', 'Fecha', 'Acci&oacute;nes');
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
                               <td><a class="player btn btn-primary" data-file="'.$record['Video']['archivo'].'" href="#"><i class="fa fa-play"></i> Reproducir</a></td>
                               <td>'.FormatComponent::defaultDate($record['Video']['fecha_alta']).'</td>
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
