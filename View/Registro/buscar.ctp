<?php

$form = '';
$buttons = '';


/* Form */

$this->Input->setData($_GET);
$this->Input->setControllerPath($controllerPath);
$form .= $this->Input->create('Registro', array('onsubmit' => '', 'type' => 'GET', 'url' => $controllerPath.'index/'));

$form .= $this->Input->element('searchAuto', '', 'hidden', '', array('value' => 0));
$form .= $this->Input->element('Registro_nombre', "Nombre", "text");
$form .= $this->Input->element('Registro_correo', "Correo", "text");

/* Buttons */

$buttons .= $this->Input->buttonSearch('Buscar', '#RegistroBuscarForm');
$buttons .= $this->Input->buttonReset('Limpiar', '#RegistroBuscarForm');

$form .= $this->Html->div('form-actions', $buttons);

$form .= $this->Input->end();

die($form);
 
?>
