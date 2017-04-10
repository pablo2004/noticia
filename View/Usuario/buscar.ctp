<?php

$form = '';
$buttons = '';


/* Form */

$this->Input->setData($_GET);
$this->Input->setControllerPath($controllerPath);
$form .= $this->Input->create('Usuario', array('onsubmit' => '', 'type' => 'GET', 'url' => $controllerPath.'index/'));

//$form .= $this->Input->element('searchAuto', '', 'hidden', '', array('value' => 1));
$form .= $this->Input->element('Usuario_rol_id', "Rol", "", "input-xlarge", array('options' => $roles));
$form .= $this->Input->element('Usuario_nombre', "Nombre");
$form .= $this->Input->element('Usuario_apellido_paterno', "Apellido Paterno");
$form .= $this->Input->element('Usuario_apellido_materno', "Apellido Materno");
$form .= $this->Input->element('Usuario_email', "Email");

/* Buttons */

$buttons .= $this->Input->buttonSearch('Buscar', '#UsuarioBuscarForm');
$buttons .= $this->Input->buttonReset('Limpiar', '#UsuarioBuscarForm');

$form .= $this->Html->div('form-actions', $buttons);

$form .= $this->Input->end();

die($form);
 
?>
