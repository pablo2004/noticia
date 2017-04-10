<?php  


$data = '';

 $data .= $this->Dom->controlBox($controllerPath);
 $data .= $this->Dom->setFormSearch("Buscar Registro", $controllerPath."buscar/", $_GET, Hash::get($_GET, 'searchAuto'));
 $data .= "<hr>";
$data .= $this->Dom->controlOrders($orden);
$data .= "<hr>";
 $data .= $this->Html->div('', $this->Dom->tableData($cabeceras, $this->Format->formatTemplateCallback($registros, $callback)));
$data .= "<hr>";
$data .= $this->Dom->getPaginator();

/* Block */
$main = $this->Block->setMainBlock("Lista de Registro", $data, 'fa fa-user');

echo $this->Block->setBody($navBar1, $main, $navBar2);

?>