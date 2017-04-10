var config = {
     'diasNombreLocal': ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
     'diasNombreCompletoLocal': ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
     'mesesNombreLocal': ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
     'mesesNombreCompletoLocal': ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
     'formatoFecha': 'yy-mm-dd'
};


appInit = function(context){

     $.emulateCheckbox(context.find(':checkbox'));

     context.find('.date-picker').each(function(){
          var datePicker = $(this);
          var defaultDate = datePicker.extendData('default-date', null);
          datePicker.datepicker({ 'changeMonth': true, 'changeYear': true, 'defaultDate': defaultDate });
     });

     context.find('.system-autorize').each(function(){
          var self = $(this);
          var onDenied = self.data('on-denied'); 
          var controller = self.data('controller');
          var action = self.data('action');
          var allow = 0;
          var filters = [];

          $.each(permits, function(key, value){
               if(value['RolPermiso']['controlador'] == 'Todo' && value['RolPermiso']['accion'] == 'todo' && value['RolPermiso']['permitir']){
                    allow++;
               }
          });

          $.each(permits, function(key, value){
               if(value['RolPermiso']['controlador'] == controller){
                    filters.push(value);
               }
          });

          $.each(filters, function(key, value){
               if(value['RolPermiso']['accion'] == action || value['RolPermiso']['accion'] == 'todo'){
                    if(value['RolPermiso']['permitir']){
                         allow++;
                    }
                    else{
                         allow--;
                    }
               }
          });

          if(allow <= 0){
               if(onDenied == 'hide'){
                    self.hide();
               }
               else if(onDenied == 'remove'){
                    self.remove();
               }
          }

     });

     context.find('.system-collapse').on("click", function(){
          var self = $(this);
          var target = $(self.data('target'));
          if(target.is(':visible')){
               target.hide(self.data('effect'));
          }
          else{
               target.show(self.data('effect'));
          }
     });

     context.find('div.form-group :input').each(function(){ //
          $(this).tooltip({
               'title': function(){
                    return $(this).data('error');
               },
               'placement': 'top',
               'trigger': 'manual',
               'html': true,
               'delay': 0,
               'animation': false
          });
     });

     context.find('div.auto-popup, pre.cake-error').each(function(){
          $.alert({'html': $(this).html()});
          $(this).hide();
     });

     context.find('.time-picker').each(function(){
          var self = $(this), val = self.val();
          if(val != ''){
               parts = val.split(":");
               self.val(parts[0]+":"+parts[1]);
          }
     });

     $.applyModule(context.find('.time-picker'), 'jquery-ui-timepicker-addon.js', function(){ $(this).timepicker({'currentText':'Ahora','closeText':'Listo','timeOnlyTitle':'Elegir Hora','timeText':'Horario','hourText':'Hora','minuteText':'Minutos'}); });
     $.applyModule(context.find('.mask-phone'), 'jquery.maskedinput.js', function(){ $(this).mask("(999) 999-9999"); });
     $.applyModule(context.find('.text-editor'), 'summernote.min.js', function(){ 
          var self = $(this);
          $.getScript($jsPath+"summernote-es-ES.min.js", function( data, textStatus, jqxhr ) {
               self.summernote({  
                    'lang': 'es-ES',
                    'height': 200,
                    'onChange': function(contents, $editable) {
                         self.val(contents.html());
                    }
               }); 
          });
     });

     context.find('.help-util').popover({});
     context.find('.xeditable').editable();

     context.find('.upload-input-config').each(function(){

          var options = $.base64.decode($(this).val());
          options = $.parseJSON(options);
          window['upload-config-'+$(this).data('code')] = options;
          id = 'upload-file-'+options['code'];

          var form = $('<form></form>', {'id': 'up-form-'+options['code'], 'class': 'form-horizontal', 'method': 'POST', 'action': options['postUrl'], 'enctype': 'multipart/form-data' });
          var dialog = $('<div></div>', {'id': 'upload-dialog-'+options['code'], 'title': options['label'] });
          dialogHtml = '<div class="center input-hide-container">';
          dialogHtml += '<div id="upload-text-'+options['code']+'" class="input-hide-text"><i class="fa fa-cloud-upload"></i> Click para '+options['label']+'</div>';
          dialogHtml += '<div id="upload-load-'+options['code']+'" class="ajax-loader center hidden-hp"></div>';
          dialogHtml += '<input id="'+id+'" type="file" name="'+id+'" class="input-hide upload-helper" />'
          dialogHtml += '</div>';
          form.append(dialogHtml);
          dialog.html(form);
          $('body').append(dialog);
          dialog.dialog({'autoOpen': false});

          var file = $('#'+id);
          options['beforeUpload'] = function(data){
               $('#upload-text-'+options['code']).hide();
               $('#upload-load-'+options['code']).show();
          };
          options['afterUpload'] = function(data){
               $('#upload-text-'+options['code']).show();
               $('#upload-load-'+options['code']).hide();
               if($.type(data) != 'number')
               {
                    if(data['data'] != '')
                    {
                         image = $.systemImage(data['data'], options['viewImage'], options['iconPath']);
                       
                         $('#'+options['id']).val(data['data']); 
                         $('#upload-preview-'+options['code']).prop('src', image);
                         $('#upload-download-'+options['code']).data('var', data['data']);
                         $('#upload-remove-'+options['code']).data('url', options['removePath']+'/'+data['data']);
                         $('#upload-dialog-'+options['code']).dialog('close');
                    }
                    if(data['error'] != '')
                    {
                         $.sticky(data['error'], "error");
                    }
               }
          };

          $.autoUpload(file, options);
     });

     context.find('.upload-remove').click(function(e){
          e.preventDefault();
          var $upload = $(this);
          var $code = $upload.data('code')
          var options = window['upload-config-'+$code];

          $.confirm({'html': 'Borrar Archivo', 'onConfirm': function(){
               $('#'+options['id']).val('');
               $.requestByData($upload);
               $.autoUploadClean($code);
          }});
     });

     context.find('.system-tooltip').each(function(){
          $(this).tooltip({'html': true, 'animation': false});
     });

     context.find('.init-xml').each(function(){
          var $link = $(this);
          $container = $($(this).data('container'));
          $.requestXML({'url': $link.data('url'), 'container': $container});
     });

     context.find('.option-bind').each(function(){
          var self = $(this);
          var options = window[self.data('handler')](self);
          $.delegateByValue(self, options);
     });

     context.find('.system-dialog').each(function(){
 
          var dialog = $(this);          
          var options = {
               'url': '',
               'width': 700,
               'height': 'auto',
               'draggable': true,
               'modal': true,
               'autoOpen': false,
               'autoLoad': true
          }; 

          options['open'] = function(event, ui){
               html = dialog.html();
               if(html == '' && options['url'] != '')
               {
                    $.requestXML({'url': options['url'], 'container': dialog, 'complete': function(){ appInit(dialog); }});
               }
          };

          options['create'] = function(event, ui){

               html = dialog.html(); 
               if(options['autoLoad'] && html == '' && options['url'] != '')
               {
                    $.requestXML({'url': options['url'], 'container': dialog, 'complete': function(){ appInit(dialog); }});
               }
          };

          data = $.dataFormat(dialog.data());
          options = $.extend({}, options, data);
          dialog.dialog(options);
     });
};

$(document).on("ready", function(){

     $.datepicker.setDefaults({
          dateFormat: config['formatoFecha'], 
          dayNamesMin : config['diasNombreLocal'], 
          monthNames: config['mesesNombreCompletoLocal'], 
          monthNamesShort: config['mesesNombreLocal']
     });

     $.fn.editableform.template = '<form class="form-inline editableform"><div class="control-group"><div><div class="editable-input"></div><div class="editable-buttons"></div></div><div class="editable-error-block"></div></div></form>';
     $.fn.editableform.buttons = '<button type="submit" class="btn btn-primary btn-small editable-submit"><i class="fa fa-check"></i></button>'+
                                '<button type="button" class="btn btn-danger btn-small editable-cancel"><i class="fa fa-times"></i></button>';   

     appInit($(this));

     /* Start Delegate Dialogs */

     $(this).on('click', '.error-tooltip-close', function(){
          var $error = $(this);
          $tooltip = $($error.data('close'));
          $tooltip.tooltip('hide');
          $tooltip.data('error', '');
     });

     $(this).on('click', '.system-dialog-button', function(e){
          e.preventDefault();
          $($(this).data('open')).dialog('open');  
     });

     $(this).on('click', ".radio-item", function(){
          var self = $(this);
          var parent = self.parent();

          parent.find(".radio-item").removeClass("active");
          self.addClass("active");
     });

     $(this).on('click', '.ajax-requester', function(){
          var $link = $(this);
          $container = $($(this).data('container'));
          $.requestXML({'url': $link.data('url'), 'container': $container});
     });

     $(this).on('click', 'a[alt=ajax-link]', function(e){
          e.preventDefault(); 
          var $link = $(this);
          $container = $($(this).data('container'));
          $.requestXML({'url': $link.prop('href'), 'container': $container });
     });
     
     $(this).on('click', '.ajax-submit', function(e){
          var self = $(this);
          data = $.dataFormat(self.data());
          data['button'] = self;
          $.sendForm(data);
          self.data('on', 1);
     });

     $(this).on('click', '.ajax-update', function(e){
          var self = $(this);
          $.updateDialog(self);
     });
     
     $(this).on('click', '.ajax-reset', function(e){
          var self = $(this);
          $(self.data('form')).reset();
     });

     $(this).on('click', '.link-request', function(e){
          e.preventDefault();
     	  var self = $(this);
     	  if(self.data('confirm') == 1){
               var html = self.data('html') || "Confirmar Accion";
               $.confirm({ "html": html, "onConfirm": function(){  $.requestByData(self); } });
     	  }
     	  else{
     	       $.requestByData(self);
     	  }
     });

     $(this).on('click', '.link-var', function(e){
          e.preventDefault();
          var self = $(this);
          variable = self.data('var');
          path = self.data('path');
          if(variable != '')
          {
               $.redirect(path+'/'+variable);
          }
          else
          {
               $.alert({'html': 'No se completo la orden.'});
          }
     });

     $(this).on('click', '.toggle-helper', function(){
          var helper = $(this);
          var object = $($helper.data('selector'));

          if(!object.is(':visible'))
          {
               object.show();
               helper.html('<i class="fa fa-minus"></i> '+helper.data('value-show'));
          }
          else
          {
               object.hide();
               helper.html('<i class="fa fa-plus"></i> '+helper.data('value-hide'));
          }
     });

     $('.check-selector').each(function(){
          $.checkboxSelector($(this), $(this).data('selectors'));
     });

     $(this).on('change', '.select-redirection', function(){
          $url = $(this).data('redirect') + $(this).val();
          $.redirect($url);
     });
     
     $(this).on('click', '.remove-checkbox-list', function(){
          var button = $(this);
          var selectors = button.data('selectors');
          var url = button.data('url');
         
          $.confirm({'html': "&iquest;Estas seguro de eliminar los elementos seleccionados?", 'dialog-height': '170', 'onConfirm': function(){
               items = $.serializeCheckboxes(selectors); 
               $.ajax({
                    'type': 'post', 
                    'url': url+items,
                    'success': function(){
                         $.reload();
                    }
               });
          }});
     });
     
     $('.check-list').each(function(){
         
          var $list = $(this);
          if(!$list.hasClass('notbind'))
          {
               var $url = $list.data('url-items');
               var $container = $list.find('.items-container');
               $.requestXML({'url': $url, 'container': $container, 'complete': function(){
                    appInit($container);
               }});
          }
        
     });
	 
     $(this).on('click', '.modal-confirm', function(e){
         
          e.preventDefault();
          var self = $(this);
          var url = self.prop('href');

          $.confirm({ 'html' : "&iquest;Estas seguro de realizar esta acci&oacute;n?", 'onConfirm': function(){
               $.redirect(url);
          }});
     
     });
     
     $(this).on("click", ".ajax-delete", function(e){
         
          e.preventDefault();
          var $this = $(this);
          var $url = $this.data('url');
	     var $redirect = $this.data('redirect');
          $this.prop('href', '#');
          
          $.confirm({'html': "&iquest;Estas seguro de eliminar este registro?", 'onConfirm': function(){
               $.ajax({
                    'type': 'post',
                    'url': $url, 
                    'error': function(xqXHR){
                         $.alert({'title': 'Error', 'html': xqXHR.responseText, 'dialog-width': 600, 'dialog-height': 400});
                         $.sticky("No se pudo completar la peticion.", "error");
                    },
                    'success': function($data){
                         $data = $.parseJSON($data);
                         var $error = $data.error;
                         if($.empty($error))
                         {
                              $.redirect($redirect);
                         }
                         else
                         {
                              $.sticky($error, "error");
                         }
                    }
               });
          }});

     });
	 
});