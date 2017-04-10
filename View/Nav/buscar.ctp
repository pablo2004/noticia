<?php

 $form = '';
 $buttons = '';

 /* Form */
 
 $this->Input->setData($_GET);
 $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Nav', array('onsubmit' => '', 'type' => 'GET', 'url' => $controllerPath.'index/'));

 $form .= $this->Input->element('searchAuto', '', 'hidden', '', array('value' => 1));
 $form .= $this->Input->element('Nav_nombre', 'Nombre');
 $form .= $this->Input->element('Nav_padre_id', 'Padre', '', '', array('options' => $navs));

 /* Buttons */

 $buttons .= $this->Input->buttonSearch('Buscar', '#NavBuscarForm');
 $buttons .= $this->Input->buttonReset('Limpiar', '#NavBuscarForm');

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();

 die($form);
 
?>
