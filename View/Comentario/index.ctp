<?php  


$data = '';

 $data .= $this->Dom->controlBox($controllerPath);
 $data .= $this->Dom->setFormSearch("Buscar Comentario", $controllerPath."buscar/", $_GET, Hash::get($_GET, 'searchAuto'));
 $data .= "<hr>";
$data .= $this->Dom->controlOrders($orden);
$data .= "<hr>";
 $data .= $this->Html->div('', $this->Dom->tableData($cabeceras, $this->Format->formatTemplateCallback($registros, $callback)));
$data .= "<hr>";
$data .= $this->Dom->getPaginator();

/* Block */
$main = $this->Block->setMainBlock("Lista de Comentarios", $data, 'fa fa-weixin');

echo $this->Block->setBody($navBar1, $main, $navBar2);

?>
<script type="text/javascript">
var path = '<?=Router::url('/comentarios/campo/'); ?>';

$(document).ready(function(){

     $('.valida-on').click(function(){
          var $self = $(this);
          var on = ($self.is(':checked')) ? 1 : 0;
          $.ajax({
               'url': path+$self.data('id')+'/validado/'+on,
               'success': function(){
                    parent = $self.parent();
                    if(on == 0){
                    	parent.removeClass('btn-success').addClass('btn-danger');
                    }
                    else{
                    	parent.removeClass('btn-danger').addClass('btn-success');
                    }
               }
          });
     });

});
</script>