<?php

$form = '';
$buttons = '';


/* Form */

$this->Input->setData($_GET);
$this->Input->setControllerPath($controllerPath);
$form .= $this->Input->create('Video', array('onsubmit' => '', 'type' => 'GET', 'url' => $controllerPath.'index/'));

$form .= $this->Input->element('searchAuto', '', 'hidden', '', array('value' => 0));
$form .= $this->Input->element('Video_registro_id', "Usuario", '', '', ['options' => AppController::getModelList("Registro") ]); 

/* Buttons */

$buttons .= $this->Input->buttonSearch('Buscar', '#VideoBuscarForm');
$buttons .= $this->Input->buttonReset('Limpiar', '#VideoBuscarForm');

$form .= $this->Html->div('form-actions', $buttons);

$form .= $this->Input->end();

die($form);
 
?>
