<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Contrasena');

 $form .= $this->Html->div('alert alert-info', '<i class="fa fa-exclamation"></i> Debes escribir el Correo electr&oacute;nico con el que te registraste en el sistema.');

 $form .= $this->Input->element('email'); 

 if(!empty($recaptcha_html))
 {
      $form .= $this->Html->div('well', $recaptcha_html);
      $form .= $this->Html->div('hide alert alert-danger error-message', '', array('id' => 'ErrorContrasenaCaptcha'));
 }
 
 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Recuperar mi Contrase&ntilde;a/Password', '#ContrasenaAltaForm', 'add', '', array('data-success' => 'done'));
 $buttons .= $this->Input->buttonReset('Limpiar', '#ContrasenaAltaForm');
 $buttons .= $this->Input->buttonLink('Volver', '/usuarios/login/?particion_id='.$particion_id, 'btn btn-primary', 'fa fa-arrow-left');

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();

 
 /* Block */
 $main = $this->Block->setMainBlock("Recuperar Contrase&ntilde;a", $form);

 echo $this->Block->setBody("", $main, $navBar2);
 
?>
<script type="text/javascript">
if(!Recaptcha)
{
     var Recaptcha = {};
     Recaptcha.reload = function(){};
}

done = function($error)
{
     if(!$error['errorExists'])
	   {
          $.alert({'html': 'Se te ha enviado un mensaje tu cuenta de correo para confirmar el cambio de contrase&ntilde;a.<div class="alert alert-danger"><b><i class="icon-exclamation"></i> Advertencia: Es posible que el correo te llege a la bandeja de Correo no deseado (SPAM).</b></div>', 'dialog-width': '500', 'dialog-height': '280'});
          Recaptcha.reload();
     }
     else
     {
          Recaptcha.reload();
     }
}
</script>
