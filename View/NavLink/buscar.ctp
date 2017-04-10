<?php

 $form = '';
 $buttons = '';

 /* Form */
 
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('NavLink', array('type' => 'GET', 'url' => $controllerPath.'index/', 'onsubmit' => 'searchNavLink(this, event)'));

 $form .= $this->Input->element('NavLink_nombre', 'Nombre');

 /* Buttons */

 $buttons .= $this->Input->buttonSearch('Buscar', '#NavLinkBuscarForm');
 $buttons .= $this->Input->buttonReset('Limpiar', '#NavLinkBuscarForm');

 $form .= $this->Html->div('form-actions', $buttons);

 $form .= $this->Input->end();

 die($form);
 
?>
