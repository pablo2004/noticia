<?php

 $form = '';
 $buttons = '';

 /* Form */
 $this->Input->setData($_GET);
 $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Rol', array('onsubmit' => '', 'type' => 'GET', 'url' => $controllerPath.'index/'));

 $form .= $this->Input->element('searchAuto', '', 'hidden', '', array('value' => 1));
 $form .= $this->Input->element('Rol_nombre', 'Nombre');

 /* Buttons */

 $buttons .= $this->Input->buttonSearch('Buscar', '#RolBuscarForm');
 $buttons .= $this->Input->buttonReset('Limpiar', '#RolBuscarForm');

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();

 die($form);
 
?>
