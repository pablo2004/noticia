<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Comentario', array('url' => $controllerPath."editar/".$id."/0"));

 $form .= $this->Input->element('registro_id', "Usuario", '', '', ['options' => AppController::getModelList("Registro")]); 
 $form .= $this->Input->element('noticia_id', "Noticia", '', '', ['options' => AppController::getModelList("Noticia")]); 
 $form .= $this->Input->element('comentario', 'Comentario');
 $form .= $this->Input->element('validado', 'Validado', 'checkbox');

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#ComentarioEditarForm', 'edit', 'Comentario Editado');
 $buttons .= $this->Input->buttonReset('Limpiar', '#ComentarioEditarForm');
 $buttons .= $this->Input->buttonDelete('Borrar', Router::url(array('action' => 'borrar', $id)), Router::url(array('action' => 'index')));
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();
 
 
 /* Block */
 $main = $this->Block->setMainBlock("Editar Comentario", $form, "fa fa-pencil");

 echo $this->Block->setBody($navBar1, $main, $navBar2);
 
?>