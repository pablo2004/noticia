<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('RolPermiso', array('id' => 'RolPermisoEditarForm'.$id, 'url' => $controllerPath."editar/$id"));
 
 $controlador = $this->request->data['RolPermiso']['controlador'];
 $acciones = (strcasecmp("Todo", $controlador ) === 0) ? array('todo' => 'todo') : RolesPermisosController::getControllerMethods($controlador);

 $form .= $this->Input->element('controlador', "Controlador", "", "call-methods", array('data-affect' => '#RolPermisoAccion'.$id, 'options' => $controladores));
 $form .= $this->Input->element('accion', "Accion", "", "", array('id' => 'RolPermisoAccion'.$id, 'options' => $acciones));
 $form .= $this->Input->element('permitir', "Permitir", 'checkbox', '', array('id' => 'RolPermisoPermitir'.$id));

 $buttons .= $this->Input->buttonRequest('Guardar', '#RolPermisoEditarForm'.$id, 'edit', 'Permiso Editado', array('data-success' => 'onRolPermisoUpdate'));
 $buttons .= $this->Input->buttonReset('Limpiar', '#RolPermisoEditarForm'.$id);

 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();
 
 die($form);

?>