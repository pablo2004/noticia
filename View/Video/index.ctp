<?php  


$data = '';

 $data .= $this->Dom->controlBox($controllerPath);
 $data .= $this->Dom->setFormSearch("Buscar Video", $controllerPath."buscar/", $_GET, Hash::get($_GET, 'searchAuto'));
 $data .= "<hr>";
$data .= $this->Dom->controlOrders($orden);
$data .= "<hr>";
 $data .= $this->Html->div('', $this->Dom->tableData($cabeceras, $this->Format->formatTemplateCallback($registros, $callback)));
$data .= "<hr>";
$data .= $this->Dom->getPaginator();

/* Block */
$main = $this->Block->setMainBlock("Lista de Videos", $data, 'fa fa-film');

echo $this->Block->setBody($navBar1, $main, $navBar2);

?>
<link href="http://vjs.zencdn.net/5.9.2/video-js.css" rel="stylesheet">

<script src="http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
<script src="http://vjs.zencdn.net/5.9.2/video.js"></script>

<script type="text/javascript">
var path = '<?=Router::url('/archivos/clips/'); ?>';
$(document).ready(function(){
  $('.player').click(function(e){
    e.preventDefault();
    var self = $(this);

        $.alert({'title': 'Ver Video', 'html': '<video id="my-video" class="video-js" controls preload="auto" style="width:450px;height:250px;" data-setup="{}"><source src="'+path+self.data('file')+'" type="video/mp4"></video>', 'dialog-width': 500, 'dialog-height': 380});
  });
});
</script> 