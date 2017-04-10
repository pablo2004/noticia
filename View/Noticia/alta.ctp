<?php
   
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Noticia');
 
 $form .= $this->Input->element('titulo', "Titulo"); 
 $form .= $this->Input->element('extracto', "Extracto");
 $form .= $this->Input->element('noticia', 'Texto', '', 'text-editor');
 $form .= $this->Input->uploadElement('imagen', array('acceptedFiles' => 'jpg,png,jpeg', 'element' => true, 'label' => 'Cargar Imagen', 'code' => 'img1', 'uploadPath' => $controllerUpload, 'imageThumbnail' => '200x150', 'removePath' => $controllerRemove, 'downloadPath' => $controllerDownload, 'postUrl' => "/util/subirImagen"));
 $form .= $this->Input->element('activa', "Activa", "checkbox", "", ['checked' => true]); 

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#NoticiaAltaForm', 'add', 'Noticia Agregada', array('data-success' => 'done'));
 $buttons .= $this->Input->buttonReset('Limpiar', '#NoticiaAltaForm');
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();

 
 /* Block */
 $main = $this->Block->setMainBlock("Agregar Noticia", $form, 'fa fa-plus');

 echo $this->Block->setBody($navBar1, $main, $navBar2);

?>
<script type="text/javascript">
done = function($result){
  if(!$result['errorExists'])
  {
    $.autoUploadClean('img1');
    $('#NoticiaNoticia').summernote('reset');
  }
}
</script>