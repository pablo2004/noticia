<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Catalogo');

 $form .= $this->Input->element('padre_id', 'Padre', '', '', array('options' => $padres));
 $form .= $this->Input->element('nombre', 'Nombre', '', '', array('data-no-reset' => 'true'));
 $form .= $this->Input->element('etiqueta');
 $form .= $this->Input->element('valor');
 $form .= $this->Input->element('activo', 'Activo', 'checkbox', '', array('checked' => 1));

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#CatalogoAltaForm', 'add', 'Catalogo Agregado');
 $buttons .= $this->Input->buttonReset('Limpiar', '#CatalogoAltaForm');
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();

 
 /* Block */
 $main = $this->Block->setMainBlock("Agregar Catalogo", $form);

 echo $this->Block->setBody($navBar1, $main, $navBar2);

?>
