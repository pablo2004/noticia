<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Video', array('url' => $controllerPath."editar/".$id."/0"));

 $form .= $this->Input->element('registro_id', "Usuario", '', '', ['options' => AppController::getModelList("Registro") ]); 
 $form .= $this->Input->uploadElement('archivo', array('acceptedFiles' => 'mp4', 'element' => true, 'label' => 'Cargar Video', 'code' => 'vid1', 'uploadPath' => $controllerUpload, 'maxSize' => 10485760, 'removePath' => $controllerRemove, 'downloadPath' => $controllerDownload, 'postUrl' => "/util/subirArchivo"));
 $form .= $this->Input->element('activo', "Activo", "checkbox"); 

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#VideoEditarForm', 'edit', 'Video Editada');
 $buttons .= $this->Input->buttonReset('Limpiar', '#VideoEditarForm');
 $buttons .= $this->Input->buttonDelete('Borrar', Router::url(array('action' => 'borrar', $id)), Router::url(array('action' => 'index')));
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();
 
 
 /* Block */
 $main = $this->Block->setMainBlock("Editar Video", $form, "fa fa-pencil");

 echo $this->Block->setBody($navBar1, $main, $navBar2);
 
?>