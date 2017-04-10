<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Video');
 
 $form .= $this->Input->element('registro_id', "Usuario", '', '', ['options' => AppController::getModelList("Registro") ]); 
 $form .= $this->Input->uploadElement('archivo', array('acceptedFiles' => 'mp4', 'element' => true, 'label' => 'Cargar Video', 'code' => 'vid1', 'uploadPath' => $controllerUpload, 'maxSize' => 10485760, 'removePath' => $controllerRemove, 'downloadPath' => $controllerDownload, 'postUrl' => "/util/subirArchivo"));
 $form .= $this->Input->element('activo', "Activo", "checkbox"); 

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#VideoAltaForm', 'add', 'Video Agregado', array('data-success' => 'done'));
 $buttons .= $this->Input->buttonReset('Limpiar', '#VideoAltaForm');
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();

 
 /* Block */
 $main = $this->Block->setMainBlock("Agregar Video", $form, 'fa fa-plus');

 echo $this->Block->setBody($navBar1, $main, $navBar2);

?>
<script type="text/javascript">
done = function($result){
	if(!$result['errorExists'])
	{
		$.autoUploadClean('vid1');
	}
}
</script>