(function($){
    
     var loader = $('<span />', {
          'title': 'Cargando...', 
          'alt': 'Cargando...', 
          'class': 'ajax-loader center'
     }); 

     var appVersion = 8.0;
     var appName = 'reunion8.0';


     $.fn.jserialize = function() 
     {

          var serialized = this.serialize();
 
          $(this).find(':checkbox').each (function() {
               var tofind = $(this).prop('name') + "=" + $(this).val();
               var toreplace = $(this).prop('name') + "=" + (this.checked ? '1' : '0');
 
               if(this.checked)   
               {
                    serialized = serialized.replace (tofind, toreplace);
               }
               else                
               {
                    serialized += "&amp;" + toreplace;
               }
          });
 
          return serialized;
     };
     
     $.fn.reset = function () 
     {
          $(this).each (function() {

               var $noReset = $(this).find(':input[data-no-reset=true]').serialize();
               var $parts = $noReset.split('&');
               this.reset();

               for($i = 0; $i < $parts.length; $i++)
               {
                    $part = $parts[$i].split('=');
                    $key = unescape($part[0]);
                    $value = unescape($part[1]);
                    $input = $(this).find(':input[name="'+$key+'"]');
                    if($input)
                    {
                         if(!$input.is(':checkbox') && !$input.is(':radio'))
                         {
                              $input.val($value);
                         }
                    }
               }
          });
     };

     $.fn.extendData = function($field, $default)
     {
          $field = $.trim($field);
          var $self = $(this);
          var $return = $self.data($field);

          if($return == null)
          {
               $return = $default;
          }

          return $return;
     }

     /*
      - BASIC FUNTIONS
     */

     $.stringCamelize = function($model, $string) 
     {
          $string = $string.replace(/_/gi, ' ');
          $string = $.ucwords($string).replace(/ /gi, '');
	     $string = $model+$string;
          return $string;
     };

     $.printFormErrors = function($form, $json) 
     {
          //alert('imprime');
          if($.type($json) != 'null')
          {
               $errors = $json;
               var $message = null;
               var $first = null;
               
               for(var $e in $errors)
               {
                    var $error = $errors[$e];
                    var $model = $form.data('model');

                    var $element = $.stringCamelize($form.data('model'), $e);
		          $message = $('#'+$element); 

                    if($first == null)
                    {
                         $first = $message;
                    }
    
                    $message.data('error', '<i data-close="#'+$element+'" class="error-tooltip-close fa fa-times"></i> '+$error+ '');
                    
                    if($message.is(':visible'))
                    {
                         $message.tooltip('show');
                    }
                    else
                    {
                         $.sticky($message.attr('name')+': '+$message.data('error'), 'error');
                    }
               }

               if($first != null)
               {
                    $first.focus();
               }
          } 
     };
     
     $.setDataBySelector = function($json) 
     {
          if($.type($json) != 'null')
          {
               $data = $json;
               
               for(var $selector in $data)
               {
                    var $data = $data[$selector]
                    var $attr = $.trim($data['attr']);
                    var $value = $.trim($data['value']);
                    
                    $($selector).prop($attr, $value);
               }
          } 
     };
     
     $.removeFormErrors = function($form) 
     {
          //alert('remove');
          $form.find(":input").each(function(){
               $(this).data('error', '');
               $(this).tooltip('hide');
          });
     };
     
     $.existsFormErrors = function($form) 
     {
          $length = $form.find(':input').filter(function(){ 
               $error = $(this).data('error'); 
               $error = ($error == 'undefined' || $error == null) ? '' : $error;
               return $error != ''; 
          }).length;

          return ($length > 0) ? true : false;
     };
     
     $.ucfirst = function($string)
     {
          return $string.charAt(0).toUpperCase() + $string.slice(1);
     };
     
     $.ucwords = function($string)
     {
          return ($string + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
               return $1.toUpperCase();
          });
     };
     
     $.empty = function($string)
     {
          $string = $.trim($string);
          return ($string == '') ? true : false;
     }
     
     $.equals = function($string1, $string2)
     {
          $string1 = $.trim($string1);
          $string2 = $.trim($string2);
          return ($string1 == $string2) ? true : false;
     };

     $.randomCode = function($length) 
     {
          var $chars = 'azertyupqsdfghjkmwxcvbn23456789AZERTYUPQSDFGHJKMWXCVBN';
          var $code = "";
          $length = parseInt($length);

          for(i = 0; i < $length; i++)
          {
               $get = Math.round(Math.random()*$chars.length);
               $code += $chars.substring($get, $get+1);
          }

          return $code;
     };

     $.redirect = function($url) 
     {
          var $url = $.trim($url);
          window.location.href = $url; 
     }

     $.reload = function()
     {
          window.location.reload();
     };

     $.setSelectData = function($object, $data)
     {
          if($object != null)
          {
               $object.find('option').remove();
       
               $.each($data, function($key, $value) {
                    $option = $('<option />', { html: $value, value: $key }); 
                    $object.append($option);
               });
          }
          else
          {
               $.alert({"html": "Error: object not in DOM."});
          }
     };

     $.elementsToJSON = function($elements, $searchs)
     { 
          // Example: $searches = {'.apply-label': 'text', '.apply-value': 'value'}
          var $json = {}, $j = 0;
          
          $elements.each(function(){

               $k = 0;
               $element = {};
               for(var $i in $searchs)
               {
                    $selector = $i;
                    $attr = $searchs[$i];
                    $get = $(this).find($selector).prop($attr);
                    $element[$k] = $get;
                    $k++;
               }

               $json[$j] = $element;
               $j++;
          });

          return $json;
     };
     
     $.setFormElementsByIds = function($item)
     {
          for(var $i in $item)
          {
               var $element = $('#'+$i);

               if($element)
               {
                    if(!$element.is(':checkbox'))
                    { 
                         $element.val($item[$i]); 
                    }
                    else
                    {
                         $checked = ($item[$i] == 1) ? true : false;
                         $element.prop('checked', $checked);
                    }
               }

               $element = null;
          }
     };

     $.getDate = function()
     {
          var $date = new Date();
          return $date.getTime();
     };
     
     $.getExtension = function($string)
     {
          $string = $string.substr(($string.lastIndexOf('.')+1));
          return $string;
     };
     
     $.getValue = function($value)
     {
          var $object = $($value);
          $value = "";

          if($object) 
          {
               $value = $.trim($object.val());
          }
          
          return $value;
     };

     $.disableForm = function($form)
     {
          if($form)
          {
               $form.find(':input').prop('disabled', true);
          }
     };
     
     $.appendAttr = function($object, $value, $attr)
     {
          if($object)
          {
               var $data = $.trim($object.prop($attr));
               $value = $.trim($value);
               
               if($.empty($data))
               {
                    $object.prop($attr, $value);
               }
               else
               {
                    $object.prop($attr, $data+'+'+$value);
               }
          }
     };

     $.findIndexByAttr = function($json, $attr, $value)
     {
          var $f = -1;

          for(var $i in $json)
          { 
               $item = $json[$i];
               if($item[$attr] == $value)
               {
                    $f = $i;
                    break;
               }
          }     

          return $f;
     };

     $.findItemByAttr = function($json, $attr, $value)
     {
          var $item = null;
          var $index = $.findIndexByAttr($json, $attr, $value);

          if($index > -1)
          {
               $item = $json[$index];
          }

          return $item;
     };

     $.getYoutubeVar = function($key)
     {
          var $return = '';
          var $match = $key.match("v=([^&]*)", "i"); 
          if($match != null)
          {
               $return = $match[0].split('=');
               $return = $return[1];
          }
          
          return $return; 
     };

     $.systemImage = function($file, $upload_path, $upload_image)
     {
          var $image = '';
          $file = $.trim($file);
          $upload_path = $.trim($upload_path);
          $upload_image = $.trim($upload_image);
          $extension = $.getExtension($file).toString().toLowerCase();

          switch($extension)
          {
               case 'jpg':
               case 'png':
               case 'jpeg':
               case 'bmp':
               case 'gif':
                    $image = $upload_path+'/'+$file;
               break;
               case 'pdf': 
                    $image = $upload_image+'/'+'upload_pdf.png';
               break;
               case 'doc': 
               case 'docx': 
                    $image = $upload_image+'/'+'upload_word.png';
               break;
               case 'xls': 
               case 'xlsx': 
                    $image = $upload_image+'/'+'upload_excel.png';
               break;
               case 'ppt': 
               case 'pptx': 
                    $image = $upload_image+'/'+'upload_powerpoint.png';
               break;
               case 'mp4': 
               case 'avi': 
               case 'mpeg': 
               case 'mkv': 
                    $image = $upload_image+'/'+'upload_video.png';
               break;
               case 'mp3': 
               case 'wav': 
               case 'ogg': 
                    $image = $upload_image+'/'+'upload_audio.png';
               break;
               case 'zip': 
               case 'rar': 
               case '7zip': 
               case 'gz': 
                    $image = $upload_image+'/'+'upload_compress.png';
               break;
               default:
                    $image = $upload_image+'/'+'upload_cancel.png';
               break;
          }

          return $image;
     };

     /* 
      - ESPECIAL FUNCTIONS
     */

     $.requestByData = function($element)
     {
          var $load = loader.clone();
          var $options = $element.data();
          var $key = $.randomCode(5);

          var $basics = {
               'type': 'POST', 
               'cache': true,
               'async': true,
               'beforeSend': function(){
                    $.alert({ 'id': $key, 'title': 'Enviando...', 'html': $load, 'dialog-modal': true });
               },
               'error': function(xqXHR){
                    $.log({'tipo': 'error', 'url': $element.data('url'), 'datos': $element.data('data'), 'mensaje': xqXHR.responseText});
                    $.alert({'title': 'Error', 'html': xqXHR.responseText, 'dialog-width': 600, 'dialog-height': 400});
               },
               'success': function($data){
                    $('#'+$key).dialog('close');
               }
          }; 

          $options = $.extend({}, $basics, $options);
          $.ajax($options);
     }

     $.requestXML = function($options)
     {
          var $load = loader.clone();
          var $container = ($.type($options['container']) == 'string') ? $($options['container']) : $options['container'];

          var $basics = {
               'type': 'GET', 
               'cache': true,
               'async': true,
               'beforeSend': function(){
                    $container.html($load);
                    $load.show();
               },
               'error': function(xqXHR){
                    $.log({'tipo': 'error', 'url': $options['url'], 'datos': $options['data'], 'mensaje': xqXHR.responseText});
                    $.alert({'title': 'Error', 'html': xqXHR.responseText, 'dialog-width': 600, 'dialog-height': 400});
                    $container.html('<div class="alert alert-danger"><i class="fa fa-times"></i> Error: no se pudo cargar el contenido.</div>');
                    $load.hide();
               },
               'success': function($data){
                    $container.html($data);
               }
          }; 

          $options = $.extend({}, $basics, $options);
          $.ajax($options);
     };

     $.dialog = function($div, $dialog)
     {
          $div = $('<div />', $div);
          $div.appendTo($('body'));
          $div.dialog($dialog);
     };

     $.confirm = function($options)
     {
          var $basics = {
               'id': null,
               'title': 'Confirmar',
               'html': 'Confirmar esta acci&oacute;n:',
               'dialog-modal': false,
               'dialog-width': 300,
               'dialog-height': 150,
               'dialog-autoOpen': true,
               'onConfirm': function(){ },
               'onCancel': function(){ }
          }; 

          $options = $.extend({}, $basics, $options);
          $options['id'] = ($options['id'] == null) ? 'alert-'+$.randomCode(5) : $options['id'];
          var $div = { 'id': $options['id'], 'title': $options['title'], 'html': $options['html'] };
          var $dialog = { 
               'modal': $options['dialog-modal'], 
               'autoOpen': $options['dialog-autoOpen'],
               'width': $options['dialog-width'],
               'height': $options['dialog-height'],
               'buttons': [
		          {
			          'html': '<i class="fa fa-check"></i> Confirmar',
			          'class': 'btn btn-success',
                         'click': function() { 
                              $(this).dialog('close'); 
                              $options['onConfirm'].call();
                         }
                    },
                    {
			          'html': '<i class="fa fa-times"></i> Cancelar',
			          'class': 'btn btn-danger',
                         'click': function() { 
                              $(this).dialog('close'); 
                              $options['onCancel'].call();
                         }
                    }
               ]
          };

          $.dialog($div, $dialog);
     };
     
     $.alert = function($options)
     {
          var $basics = {
               'id': null,
               'title': 'Alerta',
               'html': '',
               'dialog-modal': false,
               'dialog-width': 300,
               'dialog-height': 150,
               'dialog-autoOpen': true,
               'onClose': function(){ }
          }; 

          $options = $.extend({}, $basics, $options);
          $options['id'] = ($options['id'] == null) ? 'alert-'+$.randomCode(5) : $options['id'];
          var $div = { 'id': $options['id'], 'title': $options['title'], 'html': $options['html'] };
          var $dialog = { 
               'modal': $options['dialog-modal'], 
               'autoOpen': $options['dialog-autoOpen'],
               'width': $options['dialog-width'],
               'height': $options['dialog-height'],
               'buttons': [
		          {
			          'class': 'btn btn-danger',
		               'html': '<i class="fa fa-times"></i> Cerrar',
                         'click': function() { 
                              $(this).dialog('close'); 
                              $options['onClose'].call();
                         }
                    }
               ]
          };

          $.dialog($div, $dialog);
     };

     $.checkBoxEmulateSelection = function($checkbox){
          
     }

     $.emulateCheckbox = function($checkbox){
          $checkbox.each(function(){
               var checkbox = $(this);
               var i = $('<i></i>', {'class': 'fa'});
               checkbox.hide();
               checkbox.parent().append(i);

               var changeState = function(){
                    if(checkbox.is(':checked')){
                         i.addClass('fa-check-square');
                         i.removeClass('fa-square');
                    }
                    else{
                         i.addClass('fa-square');
                         i.removeClass('fa-check-square');
                    }
               };

               changeState();
               checkbox.click(changeState);

          });
     }
     
     $.checkboxSelector = function($checkbox, $selectorBoxes) {
          $checkbox.on("click", function(){
               var $elements = $($selectorBoxes);
               $elements.trigger('click');
          });
     };
     
     $.serializeCheckboxes = function($selector) {
         
          var $items = '';
         
          var $checkboxes = $($selector).filter(':checked');
          var $length = $checkboxes.length;
          var $c = 1;
         
          $checkboxes.each(function(){
             
               $check = $(this);
         
               $id = $check.data('id');
               $items += $id;
                  
               if($c < $length)
               {
                    $items += ',';
               }
               $c++;
                  
          });
         
          return $items;
     };

     $.autocompleteAndSet = function($object, $source, $meta)
     {
          $object.prop('onfocus', '');
          $object.off('focus');
     
          $object.autocomplete({ 
               'minLength': 3,
               'source': $source, 
               'select': function($event, $ui){
                    $($meta.selector).prop($meta.attr, $ui.item[$meta.val]);
               }
          });
     };

     $.sticky = function($string, $type)
     {
          $string = $.trim($string);
          $type = $.trim($type);

          var $options = {
               'text': '<b>'+$string+'</b>',
               'type': $type, 
               'layout': 'bottom',
               'timeout': 3000,
               'closeWith': ['click']
          };

          var $noty = noty($options);
     };
     
     $.stickyData = function($string, $type, $url)
     {
          $url = ($url != null) ? $.trim($url) : '';
          var $toHide = ($.empty($url)) ? ' hide' : '';

          var $hide = true;
          var $options = {
               'text': '<b>'+$string+'</b>',
               'type': $type, 
               'layout': 'bottomRight',
               'timeout': 5000,
               'closeWith': [],
               'buttons': [
                    {
                         'addClass': 'btn btn-success'+$toHide,
                         'text': '<i class="fa fa-arrow-left"></i> Ver Registro',
                         'onClick': function($noty){
                              $.redirect($url);
                         }
                    },
                    {
                         'addClass': 'btn btn-info'+$toHide,
                         'text': '<i class="fa fa-check"></i> Fijar',
                         'onClick': function($noty){
                              $hide = false;
                         }
                    },
                    {
                         'addClass': 'btn btn-danger btn-small',
                         'text': '<i class="fa fa-times"></i> Cerrar',
                         'onClick': function($noty){
                              $noty.close();
                         }
                    }
               ]
          };

          var $noty = noty($options);
          setTimeout(function(){ if($hide){ $noty.close(); } }, $options.timeout);
     }

     $.updateDialog = function($item)
     {
          var $self = $item;
          var $code = $item.data('id');
          var $model= $item.data('model');
          var $formUrl = $item.data('form-url');

          if($code !== undefined)
          {
               $boxInput = $('#'+$model+'UpdateContainer');

               $.requestXML({'url': $formUrl, 'container': $boxInput, 'complete': function(){
                    appInit($boxInput);
                    $('#'+$model+'UpdateLink').trigger('click');
               }});
          }
     }

     $.sendForm = function(options)
     {
          var optionsDefault = { 'validate': {}, 'on': 0, 'type': '', 'message': '', 'viewUrl': '', 'getUrl': 1, 'button': null, 'error': '', 'beforeProcess': '', 'beforeSend': '', 'success': '' };
          var buttonParent = null;
          var focusError = false; 
          
          options = $.extend({}, optionsDefault, options);
          validate = options['validate'];
          form = $(options['form'])

          if(options['on'] == 0)
          {
               validate['errorPlacement'] = function(error, element){
                    element.data('error', '<i data-close="#'+element.attr('id')+'" class="error-tooltip-close fa fa-times"></i> '+error.html()+'');
               
                    if(element.is(':visible'))
                    {
                         element.tooltip('show');
                         if(!focusError)
                         {
                              element.focus();
                              focusError = true;
                         }
                    }
                    else
                    {
                         $.sticky(element.attr('name')+': '+element.data('error'), "error");
                    }
               };

               validate['onsubmit'] = false;
               validate['onfocusout'] = false;
               validate['onkeyup'] = false;
               validate['onclick'] = false;

               form.validate(validate);
               form.submit(function(){ event.preventDefault(); });
          }

          $.runByName(options['beforeProcess'], options);

          if(form.valid())
          {
               $.removeFormErrors(form);

               if(options['button'] != null)
               {
                    buttonParent = options['button'].parent();
                    buttonParent.children().hide();
                    buttonParent.append(loader);
               }

               formUrl = form.prop('action');
               formData = form.jserialize();

               $.ajax({
                    'dataType': "text",
                    'url': formUrl, 
                    'type': form.prop('method'), 
                    'data': formData,
                    'error': function(xqXHR){
                         $.runByName(options['error'], options);
                         $.sticky("No se pudo enviar el formulario.", "error");
                         $.alert({'title': 'Error', 'html': xqXHR.responseText, 'dialog-width': 600, 'dialog-height': 400});
                         $.log({'tipo': 'error', 'url': formUrl, 'datos': formData, 'mensaje': xqXHR.responseText});
                    },
                    'beforeSend': function(){
                         $.runByName(options['beforeSend'], options);
                    },
                    'success': function(data){

                         data = $.trim(data);
                         stored = false;


                         if(!$.empty(data))
                         {
                              try{
                                   data = $.parseJSON(data);
                              }
                              catch(e){
                                   $.log({'tipo': 'error', 'url': formUrl, 'datos': formData, 'mensaje': data});
                                   $.alert({'title': 'Error', 'html': data, 'dialog-width': 600, 'dialog-height': 400});
                                   data = { 'stored': false, 'errors': []  };
                                   options['message'] = '';
                              }

                              $.printFormErrors(form, data['errors']);
                              stored = data['stored'];
                         } 

                         errorExists = $.existsFormErrors(form);
                         data['errorExists'] = errorExists;
                         $.runByName(options['success'], data);
                    
                         if(!$.empty(options['message']))
                         {
                              if(!errorExists && stored)
                              {  
                                   if($.equals(options['type'], 'add') && !$.empty(data['viewUrl']) && options['getUrl'] == 1)
                                   {
                                        $.stickyData(options['message'], "success", data['viewUrl']);
                                   } 
                                   else
                                   {
                                        $.sticky(options['message'], "success");
                                   }
                              }  
   
                              if(!errorExists && !stored)
                              {
                                   $.log({'tipo': 'error', 'url': formUrl, 'datos': formData, 'mensaje': data});
                                   $.sticky("No se completo la orden.", "error");
                              }
                         }
                         
                         if($.equals(options['type'], 'add'))
                         {
                              if(!errorExists)
                              {
                                   form.reset();
                              }
                         }
                    },
                    'complete': function(){
                         if(buttonParent != null)
                         {
                              buttonParent.children().show();
                              buttonParent.find('.ajax-loader').remove();
                         }
                    }
               });
          }
     }

     $.applyModule = function($object, $module, $callback){

          if($object.length > 0)
          {
               if($.inArray($module, $jsStack) === -1)
               {
                    $.ajax({
                          'dataType': "script",
                          'cache': true,
                          'error': function(xqXHR){
                              $.log({'tipo': 'error', 'url': $jsPath+$module, 'datos': '', 'mensaje': xqXHR.responseText});
                          },
                          'url': $jsPath+$module,
                          'complete': function(){
                              $jsStack.push($module);
                              $object.each($callback);
                          }
                    });
               }
               else
               {
                    $object.each($callback);
               }
          }
     }
    
     ////////////////////////////////////////////////////////////////////////////////
     // UPLOAD
     ////////////////////////////////////////////////////////////////////////////////

     $.autoUploadClean = function($code)
     {
          var options = window['upload-config-'+$code];
          $('#upload-preview-'+$code).prop('src', $.systemImage('',  options['viewImage'], options['iconPath']));
          $('#upload-download-'+$code).data('var', '');
          $('#upload-remove-'+$code).data('url', options['removePath']+'/');
     }

     $.autoUpload = function($input, $options)
     {
          var $input = ($.type($input) == 'string') ? $($input) : $input;
          var $body = $('body');
          var $code = $.randomCode(5);
          var $basics = {
               'code': $code,
               'url': null,
               'path': null,
               'beforeUpload': null,
               'afterUpload': null
          }; 

          $options = $.extend({}, $basics, $options);
     
          var $frame = $('<iframe name="upload-frame-'+$options['code']+'" id="upload-frame-'+$options['code']+'" src="'+$options['postUrl']+'" frameborder="0" width="1" height="1" style="display:none;"></iframe>'); 
          var $form = $('#up-form-'+$options['code']);
          $form.prop('target', "upload-frame-"+$options['code']);

          $body.append($frame);
          $options['file'] = $input.prop('name');

          for(var $i in $options)
          {
               $element = $('<input />', {
                    'type': 'hidden', 
                    'name': $i, 
                    'value': $options[$i]
               });

               $form.append($element);
          }

          ////////////////////////////////////////////////////////////////////////////////
          // Intervals
          ////////////////////////////////////////////////////////////////////////////////

          var $TriggerBar_Interval = null;
          var $TriggerFunction_Interval = null;

          var $TriggerFunction = function() {
            
               var $json = $.parseJSON($frame.contents().text());
               
               $json = ($.type($json) != 'object') ? {
                    'data' : '', 
                    'error' : ''
               } : $json;

               if($json['data'] != '' || $json['error'] != '')
               { 
                    clearInterval($TriggerFunction_Interval);
                    $.executeIfExists($options['afterUpload'], $json);

                    $input.val('');
                    $frame.prop('src', $frame.prop('src')+'?'+$.getDate());
               }
          };

          ////////////////////////////////////////////////////////////////////////////////

          $input.on("change", function(){ 
               $form.submit();
          });

          $form.submit(function(){ 
               $.executeIfExists($options['beforeUpload'], $input);
               $TriggerFunction_Interval = setInterval($TriggerFunction, 500);
          });

     }
     
     $.setRateStars = function($element)
     {

          var $options = {};
          var $stars_object = ($.type($element) == 'string') ? $($element) : $element;

          $options['start'] = $stars_object.extendData('start', 0);
          $options['stars'] = $stars_object.extendData('stars', 5);
          $options['url'] = $stars_object.extendData('url', 0);

          for($i = 1; $i <= $options['stars']; $i++) 
          {

               $star = $('<i />', { 'class': '' });
               if($options['start'] >= $i)
               {
                    $star.addClass('icon-star')
               }
               else
               {
                    $star.addClass('icon-star-empty')
               }

               $star.css('cursor', 'pointer');
               $star.data('value', $i);

               $star.mouseover(function(){
                    $value = $(this).data('value');
                    $stars = $stars_object.find('i');
                    $stars_selecteds = $stars.filter(":lt("+$value+")");

                    $stars.removeClass('icon-star-full');
                    $stars.addClass('icon-star-empty');
                    $stars_selecteds.removeClass('icon-star-empty');
                    $stars_selecteds.addClass('icon-star');
               });

               $star.mouseout(function(){
                    $stars = $stars_object.find('i');
                    $stars_default = $stars.filter(":lt("+$options['start']+")");

                    $stars.removeClass('icon-star');
                    $stars.addClass('icon-star-empty');
                    $stars_default.removeClass('icon-star-empty');
                    $stars_default.addClass('icon-star');
               });

               $star.click(function(){
                    $value = parseInt($(this).data('value'));
                    $stars = $stars_object.find('i'); 
                    $stars.unbind("mouseover");
                    $stars.unbind("mouseout");
                    $stars.unbind("click");
                    $.ajax({ 'url': $options['url'], 'type': 'post', 'data': "puntos="+$value });
               });

               $stars_object.append($star);
          }
     }

     $.runByName = function(name, options)
     {
          name = $.trim(name)
          if(!$.empty(name))
          {  
               window[name](options);
          }
     }

     $.executeIfExists = function($callback, $param)
     {
          $data = null;

          if($callback !== undefined && $callback !== null)
          {
               var $call = $.Callbacks();
               $call.add($callback);
               $data = $call.fire($param);
          }

          return $data;
     }

     $.delegateByValue = function($element, $options)
     {
          var $element = ($.type($element) == 'string') ? $($element) : $element;

          var $basics = {
               'type': 'checkbox',
               'event': 'click',
               'onStart': true,
               'cases': null,
               'default': null,
          }; 

          $options = $.extend({}, $basics, $options);

          $element.on($options['event'], function(){

               if($options['default'] != null)
               {
                    $.executeIfExists($options['default'], $element);
               }

               if($options['type'] == 'checkbox')
               {
                    if(!$element.is(':checked'))
                    {
                         $.executeIfExists($options['cases']['0'], $element);
                    }
                    else
                    {
                         $.executeIfExists($options['cases']['1'], $element);
                    }
               }

               if($options['type'] == 'select')
               {
                    $value = $element.val();
                    $.executeIfExists($options['cases'][$value], $element);
               }

               if($options['type'] == 'text')
               {
                    $value = $element.val();
                    $.executeIfExists($options['cases'][$value], $element);
               }

          });

          if($options['onStart'])
          {
               if($options['default'] != null)
               {
                    $.executeIfExists($options['default'], $element);
               }
               
               if($options['type'] == 'checkbox')
               {
                    if(!$element.is(':checked'))
                    {
                         $.executeIfExists($options['cases']['0'], $element);
                    }
                    else
                    {
                         $.executeIfExists($options['cases']['1'], $element);
                    }
               }

               if($options['type'] == 'select')
               {
                    $value = $element.val();
                    $.executeIfExists($options['cases'][$value], $element);
               }

               if($options['type'] == 'text')
               {
                    $value = $element.val();
                    $.executeIfExists($options['cases'][$value], $element);
               }
          }
     }

     $.dialogMapSelector = function(dialog_id, map_id, field_id, search_id){

          var map = null;
          var dialog = $(dialog_id);
          dialog.dialog({ 
               'autoOpen': false,
               'modal': false,
               'width': 600,
               'height': 600,
               'open': function( event, ui ) {

                    var coords = $(field_id), _coords = '';
                    var lat = 0, lng = 0, saved = false;

                    if(coords.val() != '')
                    {
                         _coords = coords.val().split(",");
                         lat = $.trim(_coords[0]);
                         lng = $.trim(_coords[1]);
                         saved = true;
                    }

                    if(dialog.data('loaded') == 0)
                    {

                         map = new GMaps({
                              'div': map_id,
                              'lat': lat,
                              'lng': lng,
                              'rightclick': function(e){
                                   $.confirm({'title': 'Confirmar Punto', 'onConfirm': function(){
                                        map.removeMarkers();
                                        map.addMarker({
                                             lat: e.latLng.lat(),
                                             lng: e.latLng.lng(),
                                             title: 'Ubicacion'
                                        });
                                        coords.val(e.latLng.lat()+","+e.latLng.lng());
                                   } });
                              }
                         });

                         if(saved)
                         {
                              map.addMarker({
                                   lat: lat,
                                   lng: lng,
                                   title: 'Ubicacion'
                              });
                         }

                         $(search_id).on('keyup', function(){

                              search_val = $(search_id).val();

                              if(search_val.length > 6)
                              {
                                   GMaps.geocode({
                                        address: search_val,
                                        callback: function(results, status) {
                                             if (status == 'OK') 
                                             {
                                                  var latlng = results[0].geometry.location;
                                                  map.setCenter(latlng.lat(), latlng.lng());
                                                  map.addMarker({
                                                       'lat': latlng.lat(),
                                                       'lng': latlng.lng()
                                                  });
                                             }
                                        }
                                   });
                              }
                         });

                         dialog.data('loaded', 1);
                    }

                    if(!saved)
                    {
                         GMaps.geolocate({
                              success: function(position){
                                   map.setCenter(position.coords.latitude, position.coords.longitude);
                              }
                         });
                    }

               }

          });

     }

     $.dataFormat = function(json){
          data = {};

          if($.type(json) == 'object'){

               for(var index in json){
              
                    if(index !== null){
                         attr = json[index];
                         parts = index.split("_");
                         length = parts.length;
                         if(length > 1){
                              for(var i = 1; i < length; i++){
                                   parts[i] = $.ucfirst(parts[i]);
                              }
                              index = parts.join(""); 
                         }
                    
                         data[index] = attr;
                    }
                    
               }
          }

          return data;
     }

     $.log = function($options){
          var $defaults = {
               'app': appName,
               'tipo': 'alta',
               'version': appVersion,
               'url': '',
               'datos': '',
               'mensaje': '',
               'key': 'ke234y145$'
          }

          $options = $.extend({}, $defaults, $options);

          $.ajax({
               'type': 'POST',
               'url': 'http://jolijun.com/logs/guarda.php',
               'data': $options,
               'success': function(data){
                    data = $.parseJSON(data);
                    if(data['result'] == 0){
                         console.log("Error: no log saved.");
                    }
               }
          });
     }

})(jQuery);