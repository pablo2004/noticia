<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Nav');

 $form .= $this->Input->element('padre_id', '', 'hidden');
 $form .= $this->Input->element('tipo_id', 'Tipo', '', '', array('options' => FormatComponent::getCatalog('TipoNavegacion') ));
 $form .= $this->Input->element('rol_permitido', 'Roles Permitidos', '', '', array('value' => explode(",", $record['Nav']['rol_permitido']), 'multiple' => 'multiple', 'size' => 5, 'options' => $roles));
 $form .= $this->Input->element('lado_menu', 'Lado', '', '', array('options' => FormatComponent::getCatalog('LadoNavegacion') ));
 $form .= $this->Input->element('nombre');
 $form .= $this->Input->element('enlace');
 $form .= $this->Input->element('icono');
 $form .= $this->Input->element('clase');
 $form .= $this->Input->element('orden');

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#NavEditarForm', 'edit', 'Item Editado');
 $buttons .= $this->Input->buttonReset('Limpiar', '#NavEditarForm');
 $buttons .= $this->Input->buttonDelete('Borrar', Router::url(array('action' => 'borrar', $id)), Router::url(array('action' => 'index')));
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();

 /* INICIO PERMISOS */

 $options = array(); 
 $options['id'] = 'lista_navlinks';
 $options['urlSingleItem'] = '/navs_links/item/';
 $options['urlListItems'] = '/navs_links/index/'.$id;
 $options['urlAddFormItems'] = '/navs_links/inserta/'.$id;
 $options['urlSearchFormItems'] = '/navs_links/buscar/';
 $options['urlDeleteFormItems'] = '/navs_links/eliminar/';
 $options['limit'] = 10;

 $labels = array(); 
 $labels['labelName'] = 'Links';
 $labels['labelDeleteItems'] = 'Eliminar Links';

 $form2 = $this->Dom->ajaxItemList('NavLink', $options, $labels);
 
 /* Block */
 $main = $this->Block->setMainBlock("Editar Item", $form);
 $main .= $this->Block->setMainBlock("Enlaces", $form2);

 echo $this->Block->setBody($navBar1, $main, $navBar2);
 
?>