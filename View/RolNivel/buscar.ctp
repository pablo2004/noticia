<?php

 $form = '';
 $buttons = '';

 /* Form */
 
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('RolNivel', array('type' => 'GET', 'url' => $controllerPath.'index/', 'onsubmit' => 'searchRolNivel(this, event)'));

 $form .= $this->Input->element('RolNivel_nivel_rol_id', 'Actua sobre', '', '', array('options' => $roles));

 /* Buttons */

 $buttons .= $this->Input->buttonSearch('Buscar', '#RolNivelBuscarForm');
 $buttons .= $this->Input->buttonReset('Limpiar', '#RolNivelBuscarForm');

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();

 die($form);
 
?>
