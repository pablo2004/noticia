<?php

App::uses('AuthComponent', 'Controller/Component');

class Usuario extends AppModel 
{
     public $name = "Usuario";
     public $useTable = "usuarios";
     public $displayField = "nombre_completo";
     public $actsAs = array('AutoField', 'Log', 'FilterUser', 'FilterPartition');
     public $hasMany = array();
     public $belongsTo = array('Rol', 'Particion');
     public $hasOne = array();

     public function beforeFind($query)
     {
          parent::beforeFind($query);
          $this->addCatalog("EstadoCivil", "estado_civil_id");
          $this->addCatalog("Genero", "genero_id");
     }

     public function __construct($id = false, $table = NULL, $ds = NULL)
     {
          parent::__construct($id, $table, $ds);
          $this->addVirtualField('nombre_completo', 'CONCAT('.$this->alias.'.nombre, " ", '.$this->alias.'.apellido_paterno, " ", '.$this->alias.'.apellido_materno)');
          $this->addVirtualField('nombre_completo2', 'CONCAT('.$this->alias.'.apellido_paterno, " ", '.$this->alias.'.apellido_materno, " ", '.$this->alias.'.nombre)');

          $this->setAttr('controllerPath', '/usuarios/');
          $this->setAttr('controllerUpload', '/archivos/usuarios');
          $this->setAttr('controllerRemove', '/usuarios/archivoBorrar');
          $this->setAttr('controllerDownload', '/usuarios/archivoDescargar');
          $this->setAttr('noSanitize', array('Usuario.password'));
          $this->setAttr('noApi', array('Usuario.email', 'Usuario.password'));
          $this->setAttr('controllerTitle', 'Usuarios');

          ////////////////////////////////////////////////////////////////////////
          // FORM VALIDATIONS
          ////////////////////////////////////////////////////////////////////////
          $ErrorManager = new ErrorManager($this->name);     

          $ErrorManager->add(new ErrorField('rol_id', array('numeric')));
          $ErrorManager->add(new ErrorField('email', array('email')));
          $ErrorManager->add(new ErrorField('email', array('between', 5, 50)));
          $ErrorManager->add(new ErrorField('email', array('checkUnique', array('email', 'particion_id')), "create", true, false, "Este Correo Electronico ya esta Registrado en el Sistema."));
          $ErrorManager->add(new ErrorField('email2', array('email'), "create"));
          $ErrorManager->add(new ErrorField('email2', array('equalToField', 'email'), "create"));
          $ErrorManager->add(new ErrorField('password', array('notBlank'), 'create'), true, false);
          $ErrorManager->add(new ErrorField('password', array('minLength', 8), 'create'), true, false);
          $ErrorManager->add(new ErrorField('password2', array('equalToField', 'password'), "create"));
          $ErrorManager->add(new ErrorField('password2', array('equalToField', 'password'), "update", false, true));
          $ErrorManager->add(new ErrorField('fecha_nacimiento', array('date')));
          $ErrorManager->add(new ErrorField('activo', array('numeric')));

          $this->setErrorManager($ErrorManager);

          ////////////////////////////////////////////////////////////////////////
          // SEARCH FILTERS
          ////////////////////////////////////////////////////////////////////////
          $ModelFilter = new ModelFilter();

          $ModelFilter->addFilter("Usuario.id", null, '=', array('numeric'));
          $ModelFilter->addFilter("Usuario.rol_id", null, '=', array('numeric'));
          $ModelFilter->addFilter("Usuario.nombre", null, 'LIKE', array('notBlank'));
          $ModelFilter->addFilter("Usuario.apellido_paterno", null, 'LIKE', array('notBlank'));
          $ModelFilter->addFilter("Usuario.apellido_materno", null, 'LIKE', array('notBlank'));
          $ModelFilter->addFilter("Usuario.email", null, '=', array('email'));

          $this->setAttr('filters', $ModelFilter->formatFilters());

          ////////////////////////////////////////////////////////////////////////
          // SEARCH ORDERS
          ////////////////////////////////////////////////////////////////////////

          $orders = array('Usuario.id' => 'Id', 'Usuario.nombre_completo2' => 'Nombre', 'Usuario.fecha_alta' => 'Fecha alta');
          $this->setAttr('orders', $orders);

          $headers = array('#', 'Nombre', 'Apellido Paterno', 'Apellido Materno', 'Acci&oacute;nes');
          $this->setAttr('tableHeaders', $headers);
     }

     public function getFormatCallback($record)
     {
          $format = '';

          if(is_array($record))
          {
               $path = Router::url($this->getAttr('controllerPath'));
               $fecha_alta = FormatComponent::defaultDate($record['Usuario']['fecha_nacimiento']);
               $id = $record[$this->name]['id'];
               $edit_path = $path."editar/".$id;

               $buttons = '';
               $labels = '';

               $format = '<tr>
                               <td>'.$id.'</td>
                               <td>'.$record['Usuario']['nombre'].'</td>
                               <td>'.$record['Usuario']['apellido_paterno'].'</td>
                               <td>'.$record['Usuario']['apellido_materno'].'</td>
                               <td>
                                   <a title="Editar" class="btn btn-info btn-sm system-tooltip" href="'.$path.'editar/'.$id.'"><i class="fa fa-edit"></i> </a>
                                   <a title="Borrar" class="btn btn-danger btn-sm ajax-delete system-tooltip" data-url="'.$path.'borrar/'.$id.'" data-redirect="'.$path.'" href="#"><i class="fa fa-times"></i> </a>
                                   <label for="check-to-remove-'.$id.'" class="btn btn-warning btn-sm system-tooltip" title="Seleccionar"><input id="check-to-remove-'.$id.'" style="margin:0px;" type="checkbox" class="inpu-mini remove-list" data-id="'.$id.'" /></label>
                               </td>
                          </tr>';

          }

          return $format;
     }

     public function beforeSave($options = array())
     {
          if(!empty($this->data['Usuario']['password']))
          {
               $this->data['Usuario']['password'] = AuthComponent::password($this->data['Usuario']['password']);
          }
          else
          {
               unset($this->data['Usuario']['password']);
               unset($this->data['Usuario']['password2']);
          }
            
          return true;
     }

}

?>