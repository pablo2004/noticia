<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Contrasena');

 $form .= $this->Html->div('alert alert-info', '<i class="fa fa-exclamation"></i> Para terminar el proceso de confirmaci&oacute;n solo da click en el boton <b>Contrase&ntilde;a/Password</b>.');

 if(empty($codigo))
 {
      $form .= $this->Input->element('codigo', 'Codigo', "text", "input-xlarge", array('value' => $codigo));
 }
 else
 {
      $form .= $this->Input->element('codigo', 'Codigo', "hidden", "input-xlarge", array('value' => $codigo));
 }

 if(!empty($recaptcha_html))
 {
      $form .= $this->Html->div('well', $recaptcha_html);
      $form .= $this->Html->div('hide alert alert-danger error-message', '', array('id' => 'ErrorContrasenaCaptcha'));
 } 
 
 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Confirmar cambio de Contrase&ntilde;a/Password', '#ContrasenaConfirmarForm', 'edit', '', array('data-success' => 'done'));
 $buttons .= $this->Input->buttonReset('Limpiar', '#ContrasenaConfirmarForm');
 $buttons .= $this->Input->buttonLink('Volver', '/usuarios/login/?particion_id='.$particion_id, 'btn btn-primary', 'fa fa-arrow-left');

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();
 
 /* Block */
 $main = $this->Block->setMainBlock("Confirmar cambio de Contrase&ntilde;a", $form);

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
          $.alert({'html':'Se te ha enviado un mensaje a tu cuenta de correo con la nueva contrase&ntilde;a.<div class="alert alert-danger"><b><i class="fa fa-exclamation"></i> Advertencia: Es posible que el correo te llege a la bandeja de Correo no deseado (SPAM).<b/></div>', 'dialog-width': '500', 'dialog-height': '280'});
          Recaptcha.reload();
     }
     else
     {
          Recaptcha.reload();
     }
}
</script>
