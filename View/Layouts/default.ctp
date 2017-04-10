<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
     <head>
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <?php
               $time = 2;
	             echo "<title>$title_for_layout</title>\n";
	             echo $this->Html->charset()."\n";
               echo $this->Html->meta('favicon.ico?'.$time, 'favicon.ico?'.$time, array('type' => 'icon'))."\n";

               echo '<script type="text/javascript">var permits = '.$permits.'; var $jsStack = [], $jsPath = "'.Router::url('/js/').'";</script>';

	             echo $this->Html->css(array('jquery-ui.min.css?'.$time, 'bootstrap.min.css?'.$time, 'summernote.css?'.$time, 'bootstrap-editable.css?'.$time, 'font-awesome.min.css?'.$time, 'app.css?'.$time));

               echo $this->Html->script(array('json2.js?'.$time, 'jquery.min.js?'.$time, 'jquery-migrate-1.2.1.min.js?'.$time, 'jquery.base64.min.js?'.$time, 'jquery-ui.min.js?'.$time, 'bootstrap.min.js?'.$time, 'bootstrap-editable.min.js?'.$time, 'jquery.validate.min.js?'.$time, 'messages_es.min.js?'.$time, 'jquery.noty.packaged.min.js?'.$time, 'jquery.noty.layouts.min.js?'.$time, 'app.util.js?'.$time, 'app.js?'.$time));

          ?>
          <!--[if lt IE 9]>
               <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
               <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.2.0/respond.js"></script>
          <![endif]-->
          <link href='https://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
     </head>

     <body>

          <?php
               echo $this->element('navbar');
          ?>

          <div class="container-fluid">

	         <div class="row">
             
	            <?php
	                 echo $this->fetch('content'); 
	            ?>	

              </div>
               <script type="text/javascript">
               $(document).ready(function(){

                    $('#CambioParticion').change(function(){
                         $value = $(this).val();
                              $.ajax({'type':'get', 'url':'<?php echo Router::url('/particiones/como/'); ?>'+$value, 
                                   'success': function(){ 
                                        $.sticky('Haz cambiado de particion.', 'success'); 
                                   },
                                   'error': function(){ 
                                        $.sticky('Error: no se cambio la particion.', 'error'); 
                                   }
                         });
                    }); 
               });
               </script>
              <?php
                   echo $this->Session->flash();
                   //echo $this->element('sql_dump');
              ?>

          </div>

          <script type="text/javascript">
          $(document).ready(function(){

               $('.menu-display').each(function(){
                    $(this).parent().parent().show();
               });
          });
          </script>

     </body>

</html>