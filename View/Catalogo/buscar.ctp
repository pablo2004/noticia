<?php

 $form = '';
 $buttons = '';

 /* Form */
 
 $this->Input->setData($_GET);
 $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Catalogo', array('onsubmit' => '', 'type' => 'GET', 'url' => $controllerPath.'index/'));

 $form .= $this->Input->element('searchAuto', '', 'hidden', '', array('value' => 1));
 $form .= $this->Input->element('Catalogo_nombre', 'Nombre');
 $form .= $this->Input->element('Catalogo_etiqueta', 'Etiqueta');
 $form .= $this->Input->element('Catalogo_valor', 'Valor');

 /* Buttons */

 $buttons .= $this->Input->buttonSearch('Buscar', '#CatalogoBuscarForm');
 $buttons .= $this->Input->buttonReset('Limpiar', '#CatalogoBuscarForm');

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();

 die($form);
 
?>
