<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('NavLink', array('id' => 'NavLinkEditarForm'.$id, 'url' => $controllerPath."editar/$id"));

 $form .= $this->Input->element('rol_permitido', 'Roles Permitidos', '', '', array('value' => explode(",", $record['NavLink']['rol_permitido']), 'multiple' => 'multiple', 'size' => 5, 'options' => $roles));
 $form .= $this->Input->element('nombre');
 $form .= $this->Input->element('enlace');
 $form .= $this->Input->element('icono');
 $form .= $this->Input->element('clase');
 $form .= $this->Input->element('orden');

 $buttons .= $this->Input->buttonRequest('Guardar', '#NavLinkEditarForm'.$id, 'edit', 'Item Editado', array('data-success' => 'onNavLinkUpdate'));
 $buttons .= $this->Input->buttonReset('Limpiar', '#NavLinkEditarForm'.$id);

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();
 
 die($form);
 
?>