<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('RolPermiso', array('id' => 'RolPermisoAltaForm', 'url' => $controllerPath."inserta/$id"));

 $form .= $this->Input->element('controlador', "Controlador", "", "call-methods", array('data-affect' => '#RolPermisoAccion0', 'data-no-reset' => "true", 'options' => $controladores));
 $form .= $this->Input->element('accion', "Accion", "", "", array('id' => 'RolPermisoAccion0', 'options' => array('todo' => 'todo')));
 $form .= $this->Input->element('permitir', "Permitir", "checkbox", "", array('checked' => true));

 $buttons .= $this->Input->buttonRequest('Guardar', '#RolPermisoAltaForm', 'add', 'Permiso Agregado', array('data-success' => 'onRolPermisoSave', 'data-get_url' => 0));
 $buttons .= $this->Input->buttonReset('Limpiar', '#RolPermisoAltaForm');

 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();

 
 die($form);
 
?>