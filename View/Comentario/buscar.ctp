<?php

$form = '';
$buttons = '';


/* Form */

$this->Input->setData($_GET);
$this->Input->setControllerPath($controllerPath);
$form .= $this->Input->create('Comentario', array('onsubmit' => '', 'type' => 'GET', 'url' => $controllerPath.'index/'));

$form .= $this->Input->element('searchAuto', '', 'hidden', '', array('value' => 0));
$form .= $this->Input->element('Comentario_comentario', "Comentario", "text");

/* Buttons */

$buttons .= $this->Input->buttonSearch('Buscar', '#ComentarioBuscarForm');
$buttons .= $this->Input->buttonReset('Limpiar', '#ComentarioBuscarForm');

$form .= $this->Html->div('form-actions', $buttons);

$form .= $this->Input->end();

die($form);
 
?>
