<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Rol');

 $form .= $this->Input->element('nombre');
 $form .= $this->Input->element('url', 'Url Login');

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#RolAltaForm', 'add', 'Rol Agregado');
 $buttons .= $this->Input->buttonReset('Limpiar', '#RolAltaForm');
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();

 
 /* Block */
 $main = $this->Block->setMainBlock("Agregar Rol", $form);

 echo $this->Block->setBody($navBar1, $main, $navBar2);

?>
