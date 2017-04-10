<?php
	 
 $form = '';
 $buttons = '';
 
 /* Form */

 $form .= $this->Input->setValidate("{}");
 $form .= $this->Input->setControllerPath($controllerPath);
 $form .= $this->Input->create('Reporte', ['type' => 'GET', 'onsubmit' => '']);

 $form .= $this->Input->element('on', '', 'hidden', '', array('value' => 1));
 $form .= $this->Input->element('Nav_lado_menu', 'Padre', '', '', array('options' => FormatComponent::getCatalog('LadoNavegacion', [], [0 => 'Todos']) ));


 /* Buttons */
 
 $buttons .= $this->Input->button('Descargar', 'fa fa-download', 'btn btn-primary', 'submit');
 $form .= $this->Html->div('form-actions', $buttons);
 
 $form .= $this->Input->end();


 /* Block */
 $main = $this->Block->setMainBlock("Reporte Basico", $form);

 echo $this->Block->setBody($navBar1, $main, $navBar2);

?>
