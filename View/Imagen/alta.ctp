<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Imagen');
 
 $form .= $this->Input->element('pid', "Id del Padre (opcional)"); 
 $form .= $this->Input->element('tipo_id', "Tipo", "", "", array('options' => FormatComponent::getCatalog("TipoImagen")));
 $form .= $this->Input->element('descripcion');
 $form .= $this->Input->uploadElement('archivo', array('acceptedFiles' => 'jpg,png,jpeg', 'element' => true, 'label' => 'Cargar Fotografia', 'code' => 'img1', 'uploadPath' => $controllerUpload, 'removePath' => $controllerRemove, 'downloadPath' => $controllerDownload, 'postUrl' => "/util/subirImagen"));

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#ImagenAltaForm', 'add', 'Imagen Agregada', array('data-success' => 'done'));
 $buttons .= $this->Input->buttonReset('Limpiar', '#ImagenAltaForm');
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();

 
 /* Block */
 $main = $this->Block->setMainBlock("Agregar Imagen", $form);

 echo $this->Block->setBody($navBar1, $main, $navBar2);

?>
<script type="text/javascript">
done = function($error){
	if(!$error)
	{
		$.autoUploadClean('img1');
	}
}
</script>