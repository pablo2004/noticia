<?php  

 $data = '';

 $data .= $this->Dom->controlBox($controllerPath, ['save' => []], ['check' => [], 'delete' => [], 'divider' => [], 'limit' => []]);


 $form = '';
 $buttons = '';

 $this->Input->setData($_GET);
 $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Log', array('onsubmit' => '', 'type' => 'GET', 'url' => $controllerPath.'registros/'.$model));

 foreach($fields AS $field){
      $form .= $this->Input->element($model.'_'.$field, ucfirst($field), 'text');
 }

 $buttons .= $this->Input->buttonSearch('Buscar', '#LogBuscarForm');
 $buttons .= $this->Input->buttonReset('Limpiar', '#LogBuscarForm');

 $form .= $this->Html->div('form-action', $buttons);
 $form .= $this->Input->end();

 $data .= '<div id="searchFormContainer" class="search-box hidden-hp"><div class="search-box-title"><i class="fa fa-search"></i> Buscar '.$model.'</div><div class="search-box-content">'.$form.'</div></div>';



 $data .= "<hr>";
 $data .= $this->Dom->controlOrders($orden);
 $data .= "<hr>";
 $data .= $this->Html->div('', $this->Dom->tableData($cabeceras, $this->Format->formatTemplateCallback($registros, $callback)));
 $data .= "<hr>";
 $data .= $this->Dom->getPaginator();

 /* Block */
 $main = $this->Block->setMainBlock($model." Registros", $data);

 echo $this->Block->setBody($navBar1, $main, $navBar2);

?>