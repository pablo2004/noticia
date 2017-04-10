<?php

 $form = '';
 $buttons = '';

 $form .= $this->Input->setValidate('{}');
 $form .= $this->Input->setControllerPath($controllerPath); 
 $form .= $this->Input->create('Usuario', array('class' => 'form-horizontal'));

 $form .= $this->Input->element('particion_id', '', 'hidden', '', array('value' => $particion_id));
 $form .= $this->Input->element('url', '', 'hidden', '', array('id' => 'UsuarioLoginUrl'));
 $form .= $this->Input->element('email', 'Email');
 $form .= $this->Input->element('password', 'Contrase&ntilde;a', "password", "", array('placeholder' => 'Password'));

 if(!empty($recaptcha_html))
 {
      $form .= $this->Html->div('well', $recaptcha_html);
      $form .= $this->Html->div('hide alert alert-danger error-message', '', array('id' => 'ErrorUsuarioCaptcha'));
 }

 $buttons .= $this->Input->buttonRequest('Entrar', '#UsuarioLoginForm', '', '', array('data-success' => 'login'));
 $buttons .= $this->Input->buttonReset('Limpiar', '#UsuarioLoginForm');
 $buttons .= '<br />';
 $buttons .= $this->Input->buttonLink('Olvide mi Contrase&ntilde;a', '/contrasenas/alta/?particion_id='.$particion_id, 'btn btn-primary', 'fa fa-question');
 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();


 $main = $this->Block->setMainBlock("Usuarios Registrados", $form);

 echo $this->Block->setBody("", $main, $navBar2);
 
?>
<script type="text/javascript">
$(document).ready(function(){
     if($.browser.msie)
     {
               var $message = '<i class="fa fa-ban"></i> Advertencia: El sistema solo funciona con Navegadores modernos.';
               $message += '<p align="center">';
               $message += '<a target="_blank" href="https://www.google.com/chrome"><img src="http://cdn.jolijun.com/images/chrome.png" border="0" alt="Descargar Google Chrome" title="Descargar Google Chrome" /></a> ';
               $message += '<a target="_blank" href="http://www.mozilla.org/es-MX/firefox/new/"><img src="http://cdn.jolijun.com/images/firefox.png" border="0" alt="Descargar Mozilla Firefox" title="Descargar Mozilla Firefox"" /></a> ';
               $message += '<a target="_blank" href="http://www.opera.com/"><img src="http://cdn.jolijun.com/images/opera.png" border="0" alt="Descargar Opera" title="Descargar Opera"" /></a> ';
               $message += '</p>';
               $.alert({'html': $message, 'dialog-width': '500', 'dialog-height': '220' });
     }
});

if(!Recaptcha)
{
     var Recaptcha = {};
     Recaptcha.reload = function(){};
}

login = function(data){
     if(data['errorExists'])
     {
          Recaptcha.reload();
     }
     else
     {
          $.redirect(data['export']['url']);  
     }  
};
</script>
