<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Registro', array('url' => $controllerPath."editar/".$id."/0"));

 $form .= $this->Input->element('nombre'); 
 $form .= $this->Input->element('correo', "Correo");
 $form .= $this->Input->element('telefono', 'Tel&eacute;fono');
 $form .= $this->Input->element('password', 'Contrase&ntilde;a', 'text');

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#RegistroEditarForm', 'edit', 'Registro Editado');
 $buttons .= $this->Input->buttonReset('Limpiar', '#RegistroEditarForm');
 $buttons .= $this->Input->buttonDelete('Borrar', Router::url(array('action' => 'borrar', $id)), Router::url(array('action' => 'index')));
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();
 
 
 /* Block */
 $main = $this->Block->setMainBlock("Editar Registro", $form, "fa fa-pencil");

 echo $this->Block->setBody($navBar1, $main, $navBar2);
 
?>