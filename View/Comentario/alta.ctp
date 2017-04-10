<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Comentario');
 
 $form .= $this->Input->element('registro_id', "Usuario", '', '', ['options' => AppController::getModelList("Registro")]); 
 $form .= $this->Input->element('noticia_id', "Noticia", '', '', ['options' => AppController::getModelList("Noticia")]); 
 $form .= $this->Input->element('comentario', 'Comentario');
 $form .= $this->Input->element('validado', 'Validado', 'checkbox');

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#ComentarioAltaForm', 'add', 'Comentario Agregado');
 $buttons .= $this->Input->buttonReset('Limpiar', '#ComentarioAltaForm');
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();

 
 /* Block */
 $main = $this->Block->setMainBlock("Agregar Mensaje", $form, 'fa fa-plus');

 echo $this->Block->setBody($navBar1, $main, $navBar2);

?>
