<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('RolNivel', array('id' => 'RolNivelAltaForm', 'url' => $controllerPath."inserta/$id"));

 $form .= $this->Input->element('nivel_rol_id', 'Actua sobre', '', '', array('options' => $roles));

 $buttons .= $this->Input->buttonRequest('Guardar', '#RolNivelAltaForm', 'add', 'Nivel Agregado', array('data-success' => 'onRolNivelSave', 'data-get_url' => 0));
 $buttons .= $this->Input->buttonReset('Limpiar', '#RolNivelAltaForm');

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();
 
 
 die($form);

?>
