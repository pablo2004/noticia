<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Particion');

 $form .= $this->Input->element('nombre');
 
 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#ParticionAltaForm', 'add', 'Particion Agregada');
 $buttons .= $this->Input->buttonReset('Limpiar', '#ParticionAltaForm');
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();
 
 /* Block */
 $main = $this->Block->setMainBlock("Agregar Particion", $form);

 echo $this->Block->setBody($navBar1, $main, $navBar2);

?>