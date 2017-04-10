<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('NavLink', array('id' => 'NavLinkAltaForm', 'url' => $controllerPath."inserta/$id"));

 $form .= $this->Input->element('rol_permitido', 'Roles Permitidos', '', '', array('multiple' => 'multiple', 'size' => 5, 'options' => $roles));
 $form .= $this->Input->element('nombre');
 $form .= $this->Input->element('enlace');
 $form .= $this->Input->element('icono');
 $form .= $this->Input->element('clase');
 $form .= $this->Input->element('orden', 'Orden', '', '', array('value' => 0));

 $buttons .= $this->Input->buttonRequest('Guardar', '#NavLinkAltaForm', 'add', 'Item Agregado', array('data-success' => 'onNavLinkSave', 'data-get_url' => 0));
 $buttons .= $this->Input->buttonReset('Limpiar', '#NavLinkAltaForm');

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();

 die($form);

?>
