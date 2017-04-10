<?php

class Nav extends AppModel 
{

     public $name = "Nav";
     public $useTable = "navs";
     public $displayField = "nombre";
     public $actsAs = array('AutoField', 'Log');
     public $belongsTo = array();
     public $hasMany = array('NavLink' => array('foreignKey' => 'padre_id'));

     public function __construct($id = false, $table = NULL, $ds = NULL)
     {
          parent::__construct($id, $table, $ds);
          $this->addCatalog("TipoNavegacion", "tipo_id");
          $this->addCatalog("LadoNavegacion", "lado_menu");
          
          $this->setAttr('controllerPath', '/navs/');
          $this->setAttr('controllerUpload', '/archivos/navs');
          $this->setAttr('controllerRemove', '/navs/archivoBorrar');
          $this->setAttr('controllerDownload', '/navs/archivoDescargar');
          $this->setAttr('noSanitize', array());
          $this->setAttr('noApi', array());
          $this->setAttr('controllerTitle', 'Navegacion');

          ////////////////////////////////////////////////////////////////////////
          // FORM VALIDATIONS
          ////////////////////////////////////////////////////////////////////////

          $ErrorManager = new ErrorManager($this->name);

          $ErrorManager->add(new ErrorField('nombre', array('notEmpty')));
          $ErrorManager->add(new ErrorField('nombre', array('maxLength', 100)));
          $ErrorManager->add(new ErrorField('rol_permitido', array('notEmpty')));
          $ErrorManager->add(new ErrorField('orden', array('notEmpty')));

          $this->setErrorManager($ErrorManager);

          ////////////////////////////////////////////////////////////////////////
          // SEARCH FILTERS
          ////////////////////////////////////////////////////////////////////////
          $ModelFilter = new ModelFilter();

          $ModelFilter->addFilter("Nav.padre_id", 0, '=', array());
          $ModelFilter->addFilter("Nav.nombre", null, 'LIKE', array('notEmpty'));
          $ModelFilter->addFilter("Nav.padre_id", null, '=', array('comparison', array('>', '0')));

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('Nav.id' => 'Id', 'Nav.nombre' => 'nombre');
          $this->setAttr('orders', $orders);

          $headers = array('#', 'Nombre', 'Enlace', 'Lado', 'Orden', 'Acci&oacute;nes');
          $this->setAttr('tableHeaders', $headers);
     }

     public function getFormatCallback($record)
     {
          $format = '';

          if(is_array($record))
          {
               $path = Router::url($this->getAttr("controllerPath"));
               $id = $record[$this->name]['id'];
               $record['Padre']['nombre'] = (empty($record['Padre']['nombre'])) ? 'No Tiene' : $record['Padre']['nombre'];

               $format = '<tr>
                               <td data-title="#">'.$id.'</td>
                               <td data-title="Nombre">'.$record['Nav']['nombre'].'</td>
                               <td data-title="Enlace">'.$record['Nav']['enlace'].'</td>
                               <td data-title="Lado">'.$record['LadoNavegacion']['etiqueta'].'</td>
                               <td data-title="Orden"><a data-pk="'.$id.'" id="nav_orden_'.$id.'" data-type="text" data-url="'.$path.'campo/'.$id.'/orden" href="#" class="xeditable">'.$record['Nav']['orden'].'</a></td>
                               <td data-title="Acci&oacute;nes">
                                   <a title="Editar" class="btn btn-info btn-sm system-tooltip" href="'.$path.'editar/'.$id.'"><i class="fa fa-edit"></i> </a>
                                   <a title="Borrar" class="btn btn-danger btn-sm ajax-delete system-tooltip" data-url="'.$path.'borrar/'.$id.'" data-redirect="'.$path.'" href="#"><i class="fa fa-times"></i> </a>
                                   <label for="check-to-remove-'.$id.'" class="btn btn-warning btn-sm system-tooltip" title="Seleccionar"><input id="check-to-remove-'.$id.'"  style="margin:0px;" type="checkbox" class="inpu-mini remove-list" data-id="'.$id.'" /></label>
                               </td>
                          </tr>';
          }

          return $format;
     }


}


?>
