<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate($validate);
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Rol');

 $form .= $this->Input->element('nombre');
 $form .= $this->Input->element('url', 'Url Login');

 /* Buttons */
 
 $buttons .= $this->Input->buttonRequest('Guardar', '#RolEditarForm', 'edit', 'Rol Editado');
 $buttons .= $this->Input->buttonReset('Limpiar', '#RolEditarForm');
 $buttons .= $this->Input->buttonDelete('Borrar', Router::url(array('action' => 'borrar', $id)), Router::url(array('action' => 'index')));
 $buttons .= $this->Input->buttonList();

 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();


 /* INICIO PERMISOS */

 $options = array(); 
 $options['id'] = 'lista_permisos';
 $options['urlSingleItem'] = '/roles_permisos/item/';
 $options['urlListItems'] = '/roles_permisos/index/'.$id;
 $options['urlAddFormItems'] = '/roles_permisos/inserta/'.$id;
 $options['urlSearchFormItems'] = '/roles_permisos/buscar/';
 $options['urlDeleteFormItems'] = '/roles_permisos/eliminar/';
 $options['limit'] = 10;

 $labels = array(); 
 $labels['labelName'] = 'Permisos';
 $labels['labelDeleteItems'] = 'Eliminar Permisos';
 $labels['labelReloadItems'] = 'Recargar Permisos';

 $form2 = $this->Dom->ajaxItemList('RolPermiso', $options, $labels);

 /* INICIO NIVELES */

 $options = array(); 
 $options['id'] = 'lista_niveles';
 $options['urlSingleItem'] = '/roles_niveles/item/';
 $options['urlListItems'] = '/roles_niveles/index/'.$id;
 $options['urlAddFormItems'] = '/roles_niveles/inserta/'.$id;
 $options['urlSearchFormItems'] = '/roles_niveles/buscar/';
 $options['urlDeleteFormItems'] = '/roles_niveles/eliminar/';
 
 $labels = array(); 
 $labels['labelName'] = 'Niveles';
 $labels['labelDeleteItems'] = 'Eliminar Niveles';
 $labels['labelReloadItems'] = 'Recargar Niveles';
 $form3 = $this->Dom->ajaxItemList('RolNivel', $options, $labels);

 
 /* Block */
 $main = $this->Block->setMainBlock("Editar Rol", $form);
 $main .= $this->Block->setMainBlock("Lista de Permisos", $form2);
 $main .= $this->Block->setMainBlock("Lista de Niveles Jerarquicos", $form3);

 echo $this->Block->setBody($navBar1, $main, $navBar2);
 
?>
<script type="text/javascript">
$(document).on("ready", function(){
     $(this).on("change", ".call-methods", function(){
     	var $controller = $(this);
          var $action = $($controller.data('affect')); 
          var $value = $(this).val();

          if($value != 'Todo')
          {
               $.ajax({'type': 'POST', 'url': '<?php echo Router::url('/roles_permisos/metodos/'); ?>'+$value, success: function($data){
                    $data = $.parseJSON($data);
		          $.setSelectData($action, $data);
               }});
          }
          else
          {
               $.setSelectData($action, {'todo': 'todo'});
          }
     });
});
</script>