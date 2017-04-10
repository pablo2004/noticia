<?php

$form = '';
$buttons = '';


/* Form */

$this->Input->setData($_GET);
$this->Input->setControllerPath($controllerPath);
$form .= $this->Input->create('Noticia', array('onsubmit' => '', 'type' => 'GET', 'url' => $controllerPath.'index/'));

$form .= $this->Input->element('searchAuto', '', 'hidden', '', array('value' => 0));
$form .= $this->Input->element('Noticia_titulo', "Titulo", "text");
$form .= $this->Input->element('Noticia_extracto', "Extracto", "text");

/* Buttons */

$buttons .= $this->Input->buttonSearch('Buscar', '#NoticiaBuscarForm');
$buttons .= $this->Input->buttonReset('Limpiar', '#NoticiaBuscarForm');

$form .= $this->Html->div('form-actions', $buttons);

$form .= $this->Input->end();

die($form);
 
?>
