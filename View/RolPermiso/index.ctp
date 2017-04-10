<?php  

 $data = '';
 $data .= $this->Dom->controlAjaxOrders($orden, '#'.$model.'ItemContainer');
 $data .= $this->Html->div('check-list', $this->Dom->tableData($cabeceras, $this->Format->formatTemplateCallback($registros, $callback)));
 $data .= $this->Dom->ajaxListPaginator('#'.$model.'ItemContainer');

 die($data);

?>
