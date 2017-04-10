<?php

 $form = '';
 $buttons = '';

 /* Form */
 
 $this->Input->setData($_GET);
 $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Particion', array('onsubmit' => '', 'type' => 'GET', 'url' => $controllerPath.'index/'));
 
 $form .= $this->Input->element('searchAuto', '', 'hidden', '', array('value' => 1));
 $form .= $this->Input->element('Particion_nombre', 'Nombre');

 /* Buttons */

 $buttons .= $this->Input->buttonSearch('Buscar', '#ParticionBuscarForm');
 $buttons .= $this->Input->buttonReset('Limpiar', '#ParticionBuscarForm');

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();

 die($form);
 
?>
