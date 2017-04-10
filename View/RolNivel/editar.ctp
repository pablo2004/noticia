<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('RolNivel', array('id' => 'RolNivelEditarForm'.$id, 'url' => $controllerPath."editar/$id"));

 $form .= $this->Input->element('nivel_rol_id', 'Actua sobre', '', '', array('options' => $roles));

 $buttons .= $this->Input->buttonRequest('Guardar', '#RolNivelEditarForm'.$id, 'edit', 'Permiso Editado', array('data-success' => 'onRolNivelUpdate'));
 $buttons .= $this->Input->buttonReset('Limpiar', '#RolNivelEditarForm'.$id);

 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();
 
 die($form);
 
?>
