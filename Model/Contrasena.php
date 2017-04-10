<?php

class Contrasena extends AppModel 
{
     public $name = "Contrasena";
     public $useTable = "contrasenas";
     public $displayField = "nombre";
     public $actsAs = array('AutoField'); //, 'Log'
     public $belongsTo = array('Particion');

     public function __construct($id = false, $table = NULL, $ds = NULL)
     {
          parent::__construct($id, $table, $ds);
          $this->setAttr('controllerPath', '/contrasenas/');
          $this->setAttr('controllerUpload', '/archivos/contrasenas');
          $this->setAttr('controllerRemove', '/contrasenas/archivoBorrar');
          $this->setAttr('controllerDownload', '/contrasenas/archivoDescargar');
          $this->setAttr('noSanitize', array());
          $this->setAttr('noApi', array());
          $this->setAttr('controllerTitle', 'Recuperar Contrase&ntilde;a');

          ////////////////////////////////////////////////////////////////////////
          // FORM VALIDATIONS
          ////////////////////////////////////////////////////////////////////////

          $ErrorManager = new ErrorManager($this->name);

          $ErrorManager->add(new ErrorField('particion_id', array('numeric')));
          $ErrorManager->add(new ErrorField('email', array('email')));
          $ErrorManager->add(new ErrorField('codigo', array('notEmpty')));
          $ErrorManager->add(new ErrorField('codigo', array('maxLength', 50)));
          $ErrorManager->add(new ErrorField('activo', array('numeric')));

          $this->setErrorManager($ErrorManager);

          ////////////////////////////////////////////////////////////////////////
          // SEARCH FILTERS
          ////////////////////////////////////////////////////////////////////////
          $ModelFilter = new ModelFilter();

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('Contrasena.id' => 'Id');
          $this->setAttr('orders', $orders);

     }


}


?>
