$(document).ready(function(){
     
     $(document).on("change", ".catalog-loader", function(){
          var $self = $(this);
          $.getJSON($self.data('url')+$self.data('tipo')+'/'+$self.val(), function($data){
              $.setSelectData($('#OpcionValor'+$self.data('id')), $data);
         });
     });

});

var $initOptHandler = false;
optionHandler = function($element)
{
     var $id = $element.data('id');
     var $data = {};
     $cases = {};

     $cases[0] = function(){
         $('#DivOpcionValor'+$id).find('div:first').append('<input name="data[Opcion][valor]" placeholder="Valor" data-error="" class="input-xlarge form-control"  type="text" id="OpcionValor'+$id+'" />');
     }
     $cases[1] = function(){
         $('#DivOpcionValor'+$id).find('div:first').append('<input type="hidden" name="data[Opcion][valor]" id="OpcionValor'+$id+'_" value="0" data-original-title="" title=""> <input type="checkbox" name="data[Opcion][valor]" placeholder="" data-error="" class="input-xlarge"  id="OpcionValor'+$id+'" data-original-title="" title="">');
     }
     $cases[2] = function(){
         $('#DivOpcionValor'+$id).find('div:first').append('<textarea name="data[Opcion][valor]" placeholder="Valor" data-error="" class="form-control" cols="30" rows="6" id="OpcionValor'+$id+'" data-original-title="" title=""></textarea>');
     }
     $cases[3] = function(){
         $('#DivOpcionValor'+$id).find('div:first').append('<textarea name="data[Opcion][valor]" placeholder="Valor" data-error="" class="form-control" cols="30" rows="6" id="OpcionValor'+$id+'" data-original-title="" title=""></textarea>');
         $.applyModule($('#OpcionValor'+$id), 'summernote.min.js', function(){ 

              var self = $(this);
              self.summernote({  
                   height: 200,
                   onChange: function(contents, $editable) {
                        self.val(contents.html());
                   }
              }); 

         });
     }
     $cases[4] = function(){
         $('#DivOpcionCatalogoLocal'+$id).show();
         $('#DivOpcionValor'+$id).find('div:first').append('<select name="data[Opcion][valor]" data-error="" class="input-xlarge form-control" id="OpcionValor'+$id+'" required="required" data-original-title="" title=""></select>');
         $('#OpcionCatalogoLocal'+$id).change();
     }
     $cases[5] = function(){
         $('#DivOpcionCatalogoModulo'+$id).show();
         $('#DivOpcionValor'+$id).find('div:first').append('<select name="data[Opcion][valor]" data-error="" class="input-xlarge form-control" id="OpcionValor'+$id+'" required="required" data-original-title="" title=""></select>');
         $('#OpcionCatalogoModulo'+$id).change();
     }
     $cases[6] = function(){
         $('#panel-archivo'+$id).show();
         $('#DivOpcionValor'+$id).find('div:first').append('<input name="data[Opcion][valor]" placeholder="Valor" data-error="" class="input-xlarge form-control"  type="text" id="OpcionValor'+$id+'" />');
     }

     $data['onStart'] = false;
     $data['type'] = 'select';
     $data['event'] = 'change';
     $data['cases'] = $cases;
     $data['default'] = function(){ startHandler($id); };

     if(!$initOptHandler)
     {
          startHandler(0);
          $cases[0].call();
          $initOptHandler = true;
     }
     
     return $data;
}

startHandler = function($id){
         $('#DivOpcionCatalogoLocal'+$id).hide();
         $('#DivOpcionCatalogoModulo'+$id).hide();
         $('#DivOpcionValor'+$id).show();
         $('#panel-archivo'+$id).hide();
         $('#DivOpcionValor'+$id).find(':input').remove();
         $('#DivOpcionValor'+$id).find('.note-editor').remove();
};