<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Registro');
 
 $form .= $this->Input->element('nombre'); 
 $form .= $this->Input->element('correo', "Correo");
 $form .= $this->Input->element('telefono', 'Tel&eacute;fono');
 $form .= $this->Input->element('password', 'Contrase&ntilde;a', 'text');
 
 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#RegistroAltaForm', 'add', 'Registro Agregado');
 $buttons .= $this->Input->buttonReset('Limpiar', '#RegistroAltaForm');
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();

 
 /* Block */
 $main = $this->Block->setMainBlock("Agregar Registro", $form, 'fa fa-plus');

 echo $this->Block->setBody($navBar1, $main, $navBar2);

?>