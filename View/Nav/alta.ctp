<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Nav');

 $form .= $this->Input->element('padre_id', 'Padre', '', '', array('options' => $navs));
 $form .= $this->Input->element('tipo_id', 'Tipo', '', '', array('options' => FormatComponent::getCatalog('TipoNavegacion') ));
 $form .= $this->Input->element('rol_permitido', 'Roles Permitidos', '', '', array('multiple' => 'multiple', 'size' => 5, 'options' => $roles));
 $form .= $this->Input->element('lado_menu', 'Lado', '', '', array('options' => FormatComponent::getCatalog('LadoNavegacion') ));
 $form .= $this->Input->element('nombre');
 $form .= $this->Input->element('enlace');
 $form .= $this->Input->element('icono');
 $form .= $this->Input->element('clase');
 $form .= $this->Input->element('orden', 'Orden', '', '', array('value' => 0));

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#NavAltaForm', 'add', 'Item Agregado');
 $buttons .= $this->Input->buttonReset('Limpiar', '#NavAltaForm');
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();

 
 /* Block */
 $main = $this->Block->setMainBlock("Agregar Item", $form);

 echo $this->Block->setBody($navBar1, $main, $navBar2);

?>
