<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Particion');

 $form .= $this->Input->element('nombre');
 
 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#ParticionEditarForm', 'edit', 'Particion Editada');
 $buttons .= $this->Input->buttonReset('Limpiar', '#ParticionEditarForm');
 $buttons .= $this->Input->buttonDelete('Borrar', Router::url(array('action' => 'borrar', $id)), Router::url(array('action' => 'index')));
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();
 

 /* Block */
 $main = $this->Block->setMainBlock("Editar Particion", $form);


 echo $this->Block->setBody($navBar1, $main, $navBar2);
 
?>