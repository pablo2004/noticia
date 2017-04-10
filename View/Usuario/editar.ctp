<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Usuario', array('url' => $controllerPath."editar/".$id."/0"));

 $form .= $this->Input->element('rol_id', "Rol", "", "", array('options' => $roles));
 $form .= $this->Input->element('nombre');
 $form .= $this->Input->element('apellido_paterno');
 $form .= $this->Input->element('apellido_materno');
 $form .= $this->Input->element('email'); 
 $form .= $this->Input->element('password', "Contrase&ntilde;a", "password", "", array('value' => '', 'placeholder' => '')); 
 $form .= $this->Input->element('password2', 'Confirmar Contrase&ntilde;a', "password", "", array('value' => '', 'placeholder' => '')); 
 $form .= $this->Input->element('fecha_nacimiento', "Fecha de Nacimiento", "text", "date-picker");
 $form .= $this->Input->element('estado_civil_id', "Estado Civil", "", "", array('options' => FormatComponent::getCatalog("EstadoCivil") )); 
 $form .= $this->Input->element('genero_id', "Sexo", "", "", array('options' => FormatComponent::getCatalog("Genero") ));  

 $form .= $this->Input->element('activo', "Usuario Activo", 'checkbox'); 
 $form .= $this->Input->element('codigo', "", "hidden"); 
 $form .= $this->Input->uploadElement('fotografia', array('acceptedFiles' => 'jpg,png,jpeg', 'element' => true, 'label' => 'Cargar Fotografia', 'code' => 'foto1', 'uploadPath' => $controllerUpload, 'removePath' => $controllerRemove, 'downloadPath' => $controllerDownload, 'postUrl' => "/util/subirImagen"));

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#UsuarioEditarForm', 'edit', 'Usuario Editado');
 $buttons .= $this->Input->buttonReset('Limpiar', '#UsuarioEditarForm');
 $buttons .= $this->Input->buttonDelete('Borrar', Router::url(array('action' => 'borrar', $id)), Router::url(array('action' => 'index')));
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();
 
 /* Block */
 $main = $this->Block->setMainBlock("Editar Usuario", $form);

 echo $this->Block->setBody($navBar1, $main, $navBar2);
 
?>
<script type="text/javascript">
$(document).ready(function(){
     $.applyModule($('#UsuarioCurp'), 'jquery.maskedinput.js', function(){ $(this).mask("aaaa999999********"); });
});
</script>