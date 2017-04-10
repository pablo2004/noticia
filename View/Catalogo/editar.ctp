<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Catalogo');

 $form .= $this->Input->element('padre_id', 'Padre', '', '', array('options' => $padres));
 $form .= $this->Input->element('nombre');
 $form .= $this->Input->element('etiqueta');
 $form .= $this->Input->element('valor');
 $form .= $this->Input->element('activo', 'Activo', 'checkbox');

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#CatalogoEditarForm', 'edit', 'Catalogo Editado');
 $buttons .= $this->Input->buttonReset('Limpiar', '#CatalogoEditarForm');
 $buttons .= $this->Input->buttonDelete('Borrar', Router::url(array('action' => 'borrar', $id)), Router::url(array('action' => 'index')));
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();

 
 /* Block */
 $main = $this->Block->setMainBlock("Editar Catalogo", $form);

 echo $this->Block->setBody($navBar1, $main, $navBar2);
 
?>