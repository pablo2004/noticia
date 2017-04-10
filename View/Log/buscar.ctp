<?php

 $form = '';
 $buttons = '';

 /* Form */

 $this->Input->setData($_GET);
 $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Log', array('onsubmit' => '', 'type' => 'GET', 'url' => $controllerPath.'index/'));

 $form .= $this->Input->element('searchAuto', '', 'hidden', '', array('value' => 1));
 $form .= $this->Input->element('Log_modelo', 'Modelo');
 $form .= $this->Input->element('Log_accion', 'Accion', '', '', array('options' => array('guardar' => 'guardar', 'cambiar' => 'cambiar', 'borrar' => 'borrar')));

 /* Buttons */

 $buttons .= $this->Input->buttonSearch('Buscar', '#LogBuscarForm');
 $buttons .= $this->Input->buttonReset('Limpiar', '#LogBuscarForm');

 $form .= $this->Html->div('form-action', $buttons);

 $form .= $this->Input->end();

 die($form);
 
?>
