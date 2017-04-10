<?php

 $form = '';
 $buttons = '';

 /* Form */
 
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('RolPermiso', array('type' => 'GET', 'url' => $controllerPath.'index/', 'onsubmit' => 'searchRolPermiso(this, event)'));

 $form .= $this->Input->element('RolPermiso_controlador', 'Controlador', '', '', array('options' => $controladores));


 /* Buttons */

 $buttons .= $this->Input->buttonSearch('Buscar', '#RolPermisoBuscarForm');
 $buttons .= $this->Input->buttonReset('Limpiar', '#RolPermisoBuscarForm');

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();

 die($form);
 
?>
