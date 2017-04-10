<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Noticia', array('url' => $controllerPath."editar/".$id."/0"));

 $form .= $this->Input->element('titulo', "Titulo"); 
 $form .= $this->Input->element('extracto', "Extracto");
 $form .= $this->Input->element('noticia', 'Texto', '', 'text-editor');
 $form .= $this->Input->uploadElement('imagen', array('acceptedFiles' => 'jpg,png,jpeg', 'element' => true, 'label' => 'Cargar Imagen', 'code' => 'img1', 'uploadPath' => $controllerUpload, 'imageThumbnail' => '200x150', 'removePath' => $controllerRemove, 'downloadPath' => $controllerDownload, 'postUrl' => "/util/subirImagen"));
 $form .= $this->Input->element('activa', "Activa", "checkbox", "", ['checked' => true]); 

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#NoticiaEditarForm', 'edit', 'Noticia Editada');
 $buttons .= $this->Input->buttonReset('Limpiar', '#NoticiaEditarForm');
 $buttons .= $this->Input->buttonDelete('Borrar', Router::url(array('action' => 'borrar', $id)), Router::url(array('action' => 'index')));
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();
 
 
 /* Block */
 $main = $this->Block->setMainBlock("Editar Noticia", $form, "fa fa-pencil");

 echo $this->Block->setBody($navBar1, $main, $navBar2);
 
?>