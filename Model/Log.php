<?php

class Log extends AppModel 
{
     public $name = "Log";
     public $useTable = "logs";
     public $displayField = "";
     public $actsAs = array('AutoField');
     public $hasMany = array();
     public $belongsTo = array('Usuario', 'Particion');


     public function __construct($id = false, $table = NULL, $ds = NULL)
     {
          parent::__construct($id, $table, $ds);
          $this->setAttr('controllerPath', '/logs/');
          $this->setAttr('controllerUpload', '/archivos/logs');
          $this->setAttr('controllerRemove', '/logs/archivoBorrar');
          $this->setAttr('controllerDownload', '/logs/archivoDescargar');
          $this->setAttr('noSanitize', array());
          $this->setAttr('noApi', array());
          $this->setAttr('controllerTitle', 'Logs');

          ////////////////////////////////////////////////////////////////////////
          // FORM VALIDATIONS
          ////////////////////////////////////////////////////////////////////////
          $ErrorManager = new ErrorManager($this->name);        

          $this->setErrorManager($ErrorManager);

          ////////////////////////////////////////////////////////////////////////
          // SEARCH FILTERS
          ////////////////////////////////////////////////////////////////////////
          $ModelFilter = new ModelFilter();

          $ModelFilter->addFilter("Particion.nombre", null, '=', array('notEmpty'));
          $ModelFilter->addFilter("Log.modelo", null, '=', array('notEmpty'));
          $ModelFilter->addFilter("Log.accion", null, '=', array('notEmpty'));
          $ModelFilter->addFilter("Usuario.nombre_completo", null, 'LIKE', array('notEmpty'));

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('Log.id' => 'id', 'Log.modelo' => 'modelo', 'Log.accion' => 'accion');
          $this->setAttr('orders', $orders);

          $headers = array('#', 'Modulo', 'Acci&oacute;n', 'Registro', 'Usuario', 'Fecha');
          $this->setAttr('tableHeaders', $headers);
     }

     public function getFormatCallback($record)
     {
          $format = '';

          if(is_array($record))
          {
               $path = Router::url($this->getAttr('controllerTitle'));
               $id = $record[$this->name]['id'];
               
               $format = '<tr>
                               <td data-title="#">'.$record['Log']['id'].'</td>
                               <td data-title="Modelo">'.$record['Log']['modelo'].'</td>
                               <td data-title="Acci&oacute;n">'.$record['Log']['accion'].'</td>
                               <td data-title="Registro">'.$record['Log']['registro'].'</td>
                               <td data-title="Usuario">'.$record['Usuario']['nombre_completo'].'</td>
                               <td data-title="Fecha">'.$record['Log']['fecha_alta'].'</td>
                          </tr>';

          }

          return $format;
     }

}

?>
