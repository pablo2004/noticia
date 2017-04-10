<?php

class NavLink extends AppModel 
{
     public $name = "NavLink";
     public $useTable = "navs";
     public $displayField = "nombre";
     public $actsAs = array('AutoField', 'Log');
     public $belongsTo = array();

     public function __construct($id = false, $table = NULL, $ds = NULL)
     {
          parent::__construct($id, $table, $ds);
          $this->setAttr('controllerPath', '/navs_links/');
          $this->setAttr('controllerUpload', '/archivos/navs_links');
          $this->setAttr('controllerRemove', '/navs_links/archivoBorrar');
          $this->setAttr('controllerDownload', '/navs_links/archivoDescargar');
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

          $this->setErrorManager($ErrorManager);

          ////////////////////////////////////////////////////////////////////////
          // SEARCH FILTERS
          ////////////////////////////////////////////////////////////////////////
          $ModelFilter = new ModelFilter();

          $ModelFilter->addFilter("NavLink.nombre", null, 'LIKE', array('notEmpty'));

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('NavLink.id' => 'Id', 'NavLink.nombre' => 'nombre');
          $this->setAttr('orders', $orders);

          $headers = array('#', 'Nombre', 'Enlace', 'Orden', 'Acci&oacute;nes');
          $this->setAttr('tableHeaders', $headers);
     }

     public function getFormatCallback($record)
     {
          $format = '';

          if(is_array($record))
          {
               $path = Router::url($this->getAttr('controllerPath'));
               $id = $record[$this->name]['id'];
               $form_url = $path."editar/".$id;

               $format = '<tr data-id="'.$id.'">
                               <td data-title="#">'.$id.'</td>
                               <td data-title="Nombre">'.$record['NavLink']['nombre'].'</td>
                               <td data-title="Enlace">'.$record['NavLink']['enlace'].'</td>
                               <td data-title="Orden"><a data-pk="'.$id.'" id="navlink_orden_'.$id.'" data-type="text" data-url="'.$path.'campo/'.$id.'/orden" href="#" class="xeditable">'.$record['NavLink']['orden'].'</a></td>
                               <td data-title="Acci&oacute;nes">
                                    <button data-width="600" type="button" title="Editar" class="btn btn-info btn-sm ajax-update system-tooltip" data-id="'.$id.'" data-success="on'.$this->name.'Update" data-model="'.$this->name.'" data-form-url="'.$form_url.'"><i class="fa fa-edit"></i></button>
                                    <a data-title="Borrar" class="btn btn-danger btn-sm system-tooltip ajax-delete" href="#" data-url="'.$path.'borrar/'.$id.'" data-redirect=""><i class="fa fa-times"></i></a>
                                    <label for="check-to-remove-'.$this->alias.'-'.$id.'" class="btn btn-warning btn-sm" title="Seleccionar"><input id="check-to-remove-'.$this->alias.'-'.$id.'" style="margin:0px;" type="checkbox" class="inpu-mini check-item" data-id="'.$id.'" /></label>
                               </td>
                          </tr>';
          }

          return $format;
     }


}


?>
