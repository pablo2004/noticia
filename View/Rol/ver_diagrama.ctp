<?php  

 $data = '';

 $data .= '<ul class="list-group">';
 
 foreach($datos AS $id => $rol)
 {
      $data .= '<li class="list-group-item">
                    <i class="fa fa-user"></i> '.$rol['jerarquia'].'
                    <a title="Agregar Subnivel a: '.$rol['nombre'].'" data-title="Agregar Subnivel a: '.$rol['nombre'].'" data-id="'.$id.'" class="agrega-subnivel system-tooltip btn btn-success btn-sm pull-right" href="#"><i class="fa fa-star"></i></a> 
                    <a title="Permisos de: '.$rol['nombre'].'" data-id="'.$id.'" class="system-tooltip btn btn-info btn-sm pull-right" href="'.Router::url('/roles/editar/'.$id).'"><i class="fa fa-lock"></i></a>

                </li>';
 }

 $data .= '</ul>';

 /* Block */
 $main = $this->Block->setMainBlock("Lista de Roles", $data);

 echo $this->Block->setBody($navBar1, $main, $navBar2);

?>
<script type="text/javascript">
var path = '<?php echo Router::url("/"); ?>';

onRolNivelSave = function($result){
     if(!$result['errorExists']){
          $.reload();
     }
}

$(document).ready(function(){

	 $(this).on('click', '.agrega-subnivel', function(event){
          event.preventDefault();
          var $self = $(this);
          $.ajax({
               'url': path+"roles_niveles/inserta/"+$self.data('id'),
               'success': function($html){
                    $.alert({
                         'id': 'agregaNivel',
                    	'title': $self.data('title'), 
                    	'html': $html,
                    	'dialog-width': 600,
                    	'dialog-height': 240
                    });
                    $('#agregaNivel').css({'overflow': 'hidden'});
               }
          });

	 });
});
</script>