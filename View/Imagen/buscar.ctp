<?php

$form = '';
$buttons = '';


/* Form */

$this->Input->setData($_GET);
$this->Input->setControllerPath($controllerPath);
$form .= $this->Input->create('Imagen', array('onsubmit' => '', 'type' => 'GET', 'url' => $controllerPath.'index/'));

$form .= $this->Input->element('searchAuto', '', 'hidden', '', array('value' => 1));
$form .= $this->Input->element('Imagen_descripcion', "Descripcion", "text");

/* Buttons */

$buttons .= $this->Input->buttonSearch('Buscar', '#ImagenBuscarForm');
$buttons .= $this->Input->buttonReset('Limpiar', '#ImagenBuscarForm');

$form .= $this->Html->div('form-actions', $buttons);

$form .= $this->Input->end();

die($form);
 
?>
