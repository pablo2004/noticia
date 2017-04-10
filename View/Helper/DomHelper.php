<?php


class DomHelper extends AppHelper
{

     public $helpers = array('Html', 'Form', 'Paginator');

     public function tableData($headers, $body, $options = array())
     {
          $html = '';

          if(!array_key_exists('class', $options))
          {
               $options['class'] = 'table table-bordered table-striped table-condensed';
          }

          $head_tr = '';

          foreach($headers AS $head)
          {
               $head_tr .= '<th data-title="'.$head.'">'.$head.'</th>';
          }

          $headers = '<thead><tr>'.$head_tr.'</tr></thead>';
          $body = '<tbody>'.$body.'</tbody>';
          $html = "\n\n<div class=\"table-responsive\">".$this->Html->tag('table', $headers.$body, $options)."</div>\n\n";

          return $html;
     }

     /*
     // AJAX
     */
     public function ajaxListPaginator($container)
     {
          $string = '';
          $container = trim($container);

          $string .= '<ul class="pagination pagination-centered ajax-pagination">';
          //$string .= '<ul>';
          $string .= $this->Paginator->prev('< Anterior', array('tag'=> 'li', 'alt' => 'ajax-link', 'data-container' => $container), null, array('tag' => 'li', 'disabledTag' => 'a', 'class' => 'disabled'));
          $string .= $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'alt' => 'ajax-link', 'data-container' => $container, 'currentTag' => 'a', 'currentClass' => 'disabled'));
          $string .= $this->Paginator->next('Siguiente >', array('tag' => 'li', 'alt' => 'ajax-link', 'data-container' => $container), null, array('tag' => 'li', 'disabledTag' => 'a', 'class' => 'disabled'));
          //$string .= '</ul>';
          $string .= '</ul>';

          return $string;
     }

     public function controlAjaxOrders($fields, $container)
     {
          $string = '';
          $container = trim($container);
 
          if(is_array($fields))
          {
               $string .= '<a class="btn btn-primary btn-sm disabled" href="#"> Ordenar por:</a> ';
               $sortDir = $this->Paginator->sortDir();               
               $sortKey = $this->Paginator->sortKey(); 
   
               foreach($fields AS $key => $title)
               {
                    $class = ' btn-default';
                    $icon = '<i class="fa fa-arrow-down"></i>';
                    if($sortKey == $key)
                    {
                         $class = ' btn-info';
                         if($sortDir == 'desc')
                         {
                              $icon = '<i class="fa fa-arrow-up"></i>';
                         }
                         else
                         {
                              $icon = '<i class="fa fa-arrow-down"></i>';
                         }
                    }

                    $string .= $this->Paginator->sort($key, $icon.' '.$title, array('alt' => 'ajax-link', 'data-container' => $container, 'title' => 'Ordenar por: '.$title, 'class' => 'system-tooltip btn btn-sm'.$class, 'escape' => false))." ";
               }
          }

          return '<div>'.$string.'</div>';
     }

     public function ajaxItemList($model, $options = [], $labels = [], $tabs = [])
     {
          $defaults = array(
               'id' => 0,
               'limit' => 5,
               'urlSingleItem' => '',
               'urlListItems' => '',
               'urlAddFormItems' => '',
               'urlSearchFormItems' => '',
               'urlDeleteFormItems' => '',
               'showControlData' => true,
               'showButtonData' => true,
               'activeTab' => 'Records'
          );

          $options = array_merge($defaults, $options);
          extract($options);

          $labelsDefaults = array(
               'labelName' => 'Items',
               'labelAddItem' => 'Agregar Item',
               'labelDeleteItems' => 'Eliminar Items',
               'labelReloadItems' => 'Recargar Lista'
          );
          
          $labels = array_merge($labelsDefaults, $labels);
          extract($labels);

          $tabsDefault = array(
               'tabRecords' => 'Registros',
               'tabSearch' => 'Buscar',
               'tabAdd' => 'Alta',
               'tabUpdate' => 'Editar'
          );

          $tabs = array_merge($tabsDefault, $tabs);
          extract($tabs);

          $limit = intval($limit);
          $urlSingleItem = Router::url($urlSingleItem); 
          $urlListItems = Router::url($urlListItems);
          $urlAddFormItems = Router::url($urlAddFormItems);
          $urlSearchFormItems = Router::url($urlSearchFormItems);
          $urlDeleteFormItems = Router::url($urlDeleteFormItems);

          $arrayLimits = array(5 => 5, 10 => 10, 20 => 20, 50 => 50, 100 => 100);

          $buttons = '';
          $buttons .= ' <button class="btn btn-danger remove-checkbox-list" data-selectors="#'.$id.' .check-item" type="button" data-url="'.$urlDeleteFormItems.'"><i class="fa fa-trash-o"></i> '.$labelDeleteItems.'</button>';
          $buttons .= ' <button class="btn btn-info" id="'.$model.'ReloadList" type="button"><i class="fa fa-refresh"></i> '.$labelReloadItems.'</button>';
          $buttons = ($showButtonData) ? '<div class="form-actions">'.$buttons.'</div>' : '';

          $headersControllers  = '';
          $headersControllers .= '<label for="'.$model.'CheckList" class="btn btn-warning pull-right"><input id="'.$model.'CheckList" class="check-selector" data-selectors="#'.$id.' .check-item" type="checkbox"></label>';
          $headersControllers .= '<div id="'.$model.'PaginationLimit" class="btn-group pull-right" style="margin-right:5px;">';
          
          foreach($arrayLimits AS $key => $value)   
          {
               if($key == $limit)
               {
                    $headersControllers .= '<a class="radio-item btn btn-default btn-sm active" data-limit="'.$key.'" href="'.$urlListItems.'/'.$key.'" alt="ajax-link" data-container="#'.$model.'ItemContainer">'.$value.'</a>';
               }
               else
               {
                    $headersControllers .= '<a class="radio-item btn btn-default btn-sm" data-limit="'.$key.'" href="'.$urlListItems.'/'.$key.'" alt="ajax-link" data-container="#'.$model.'ItemContainer">'.$value.'</a>';
               }
          }  
          
          $headersControllers .= '</div>';
          $headersControllers .= '<div class="clearfix"></div>';

          $headersControllers = ($showControlData) ? '<div>'.$headersControllers.'</div><hr>' : '';

          $itemContainer = '<div id="'.$model.'ItemContainer" class="items-container"></div>';

          $listContainerHtml = '<div class="check-list" id="'.$id.'" data-url-items="'.$urlListItems.'">'.$headersControllers.$itemContainer.'</div>';
          $addContainerHtml = '<div id="'.$model.'AddContainer"></div>';
          $searchContainerHtml = '<div id="'.$model.'SearchContainer"></div>';
          $updateContainerHtml = '<div id="'.$model.'UpdateContainer"><div class="alert alert-warning"><i class="fa fa-times-circle-o"></i> Error: Registro no seleccionado.</div></div>';

          $script = '<script type="text/javascript">
                           var $pathName = window.location.pathname;
                           $(document).on("ready", function(){

                                $.requestXML({"url": "'.$urlAddFormItems.'?isAjaxRequest=1", "container": $("#'.$model.'AddContainer"), "complete": function(){ appInit($("#'.$model.'AddContainer")); } });
                                $.requestXML({"url": "'.$urlSearchFormItems.'?isAjaxRequest=1", "container": $("#'.$model.'SearchContainer"), "complete": function(){ appInit($("#'.$model.'SearchContainer")); } })

                                $("#'.$model.'ReloadList").on("click", function(){
                                     $.requestXML({"url": "'.$urlListItems.'/"+get'.$model.'Limit()+"?clearcache=1&isAjaxRequest=1", "container": $("#'.$model.'ItemContainer"), "complete": function(){ appInit($("#'.$model.'ItemContainer"));  } })
                                });
                                
                                $("#'.$model.'Add").on("dialogclose", function(){ 
                                     $.removeFormErrors($(this).find("form").first());
                                });

                                $("#'.$model.$activeTab.'Link").trigger("click");
                           });

                           on'.$model.'Save = function(data) {
                                 if(!data["errorExists"])
                                 {
                                      $.requestXML({"url": "'.$urlListItems.'/"+get'.$model.'Limit()+"?clearcache=1&isAjaxRequest=1", "container": $("#'.$model.'ItemContainer"), "complete": function(){ appInit($("#'.$model.'ItemContainer"));  } });
                                 }
                           }

                           get'.$model.'Limit = function(){
                                var $limit = $("#'.$model.'PaginationLimit a.active").data("limit");
                                return $limit;
                           }

                           on'.$model.'Update = function(data) {
                                 if(!data["errorExists"])
                                 {
                                      $.ajax({
                                           "url": "'.$urlSingleItem .'/"+data["id"]+"?isAjaxRequest=1", 
                                           "success": function(html){
                                                selector = "[data-id="+data["id"]+"]";
                                                item = $("#'.$model.'ItemContainer").find(selector);
                                                item.replaceWith(html);
                                                appInit($("#'.$model.'ItemContainer").find(selector));
                                           }
                                      });
                                 }
                           }

                           search'.$model.' = function(form, event)
                           {
                                event.preventDefault();
                                $form = $(form);
                                $data = $form.jserialize();
                                $.requestXML({"url": "'.$urlListItems.'/"+get'.$model.'Limit()+"/?"+$data, "container": $("#'.$model.'ItemContainer") });
                                $("#'.$model.'RecordsLink").trigger("click");
                           }
                      </script>';

          $divTabContent = '<div id="'.$model.'Tabs">

               <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation"><a id="'.$model.'RecordsLink" href="#'.$model.'RecordsTab" aria-controls="records" role="tab" data-toggle="tab"><i class="fa fa-list"></i> '.$tabRecords.'</a></li>
                    <li role="presentation"><a id="'.$model.'SearchLink" href="#'.$model.'SearchTab" aria-controls="search" role="tab" data-toggle="tab"><i class="fa fa-search"></i> '.$tabSearch.'</a></li>
                    <li role="presentation"><a id="'.$model.'AddLink" href="#'.$model.'AddTab" aria-controls="add" role="tab" data-toggle="tab"><i class="fa fa-plus"></i> '.$tabAdd.'</a></li>
                    <li role="presentation"><a id="'.$model.'UpdateLink" href="#'.$model.'UpdateTab" aria-controls="add" role="tab" data-toggle="tab"><i class="fa fa-pencil"></i> '.$tabUpdate.'</a></li>
               </ul>

               <div class="tab-content">
                     <div role="tabpanel" class="tab-pane" id="'.$model.'RecordsTab">
                          <br />'.$listContainerHtml.$buttons.'
                     </div>
                     <div role="tabpanel" class="tab-pane" id="'.$model.'SearchTab">
                          <br />'.$searchContainerHtml.'
                     </div>
                     <div role="tabpanel" class="tab-pane" id="'.$model.'AddTab">
                          <br />'.$addContainerHtml.'
                     </div>
                     <div role="tabpanel" class="tab-pane" id="'.$model.'UpdateTab">
                          <br />'.$updateContainerHtml.'
                     </div>
               </div>

          </div>';

          $divTabContent .= $script;

          return $divTabContent;
     }

     /*
     // NORMAL
     */

     public function getPaginator()
     {
          $string = '';
          $string .= '<ul class="pagination pagination-centered">';
          //$string .= '<ul>';
          $string .= $this->Paginator->prev('< Anterior', array('tag'=> 'li'), null, array('tag' => 'li', 'disabledTag' => 'a', 'class' => 'disabled'));
          $string .= $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentTag' => 'a', 'currentClass' => 'disabled'));
          $string .= $this->Paginator->next('Siguiente >', array('tag'=>'li'), null, array('tag' => 'li', 'disabledTag' => 'a', 'class' => 'disabled'));
          //$string .= '</ul>';
          $string .= '</ul>';
          return $string;
     }

     public function setFormSearch($title, $path, $params = [], $visible = 1, $id = "searchForm")
     {
          $url = Router::url($path); 
          if(is_array($params)){
               if(sizeof($params) > 0){
                    $url .= "?".http_build_query($params);
               }
          }

         
          $form = $this->Html->div("init-xml search-box-content", "", array('id' => $id, 'data-container' => '#'.$id, 'data-url' => $url));

          $modalContent = '<div id="'.$id.'Container" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title"><i class="fa fa-search"></i> '.$title.'</h4>
                </div>
                <div class="modal-body">
                  '.$form.'
                </div>
              </div>
            </div>
          </div>';

          if($visible == 1){
               $modalContent .= '
               <script type="text/javascript">
                    $(document).ready(function(){
                    $("#'.$id.'Container").modal("show");
               });
               </script>';
          }

          return $modalContent;
     }

     public function showConditions($conditions, $controllerPath)
     {
          $string = '';
          $excludes = array('controller_path', 'clearcache', 'searchAuto');

          if(is_array($conditions)) 
          {
               foreach($excludes AS $exclude)
               { 
                    unset($conditions[$exclude]);
               }
               $conditions = array_filter($conditions, "Validation::notEmpty");
               $size = sizeof($conditions);

               if($size > 0)
               {
                    $string .= '<hr>';
                    $string .= '<div class="alert alert-warning">';
                    $string .= '<span class="label label-warning"><i class="fa fa-search"></i> Filtros Activos:</span>'; 
                   
                    foreach($conditions AS $key => $value)
                    {
                         $conditionsFormat = $conditions;
                         unset($conditionsFormat[$key]);
                         $conditionsFormat = http_build_query($conditionsFormat);  
                         $string .= '&nbsp;<a href="'.Router::url($controllerPath).'?'.$conditionsFormat.'"><span class="label label-success"><i class="fa fa-times"></i> '.self::formatFilter($key).': '.$value.'</span></a>';
                                             
                    }

                    $string .= '</div>';
               }
          }

          return $string;
     }

     public function controlBox($path, $leftLinks = array(), $rightLinks = array())
     {
          $path = Router::url($path);
          App::uses("SessionComponent", "Controller/Component");
          $Session = new SessionComponent(new ComponentCollection());
          $here = Hash::get($_SERVER, 'REQUEST_URI');
          $divider = '';

          if(!isset($_GET['clearcache'])){
               $divider = (strpos($here, '?') === false) ? '?' : '&';
               $divider .= 'clearcache=1';
          }

          $leftLinksDefaults = [
               'save' => ['li' => [], 'tag' => ['type' => 'a', 'href' => $path.'alta', 'text' => 'Altas'], 'i' => ['class' => 'fa fa-plus']],
               'search' => ['li' => [], 'tag' => ['type' => 'a', 'href' => '#', 'text' => 'Buscar', 'data-toggle' => 'modal', 'data-target' => '#searchFormContainer', 'data-effect' => ''], 'i' => ['class' => 'fa fa-search']]
          ];

          $rightLinksDefaults = [
               'check' => ['li' => [], 'tag' => ['type' => 'a', 'href' => '#', 'text' => 'Marcar Registros', 'class' => 'check-selector', 'data-selectors' => '.remove-list'], 'i' => ['class' => 'fa fa-check-square-o']],
               'delete' => ['li' => [], 'tag' => ['type' => 'a', 'data-url' => $path.'eliminar/', 'href' => '#', 'text' => 'Eliminar Registros', 'class' => 'remove-checkbox-list', 'data-selectors' => '.remove-list'], 'i' => ['class' => 'fa fa-times']],
               'refresh' => ['li' => [], 'tag' => ['type' => 'a', 'href' => $here.$divider, 'text' => 'Recargar'], 'i' => ['class' => 'fa fa-refresh']],
               'divider' => ['li' => ['class' => 'divider']],
               'limit' => ['li' => [], 'tag' => ['div' => false, 'style' => 'margin:5px 10% 5px 10%;width:80%;', 'label' => false, 'type' => 'select', 'value' => $Session->read('limit'), 'options' => ['10' => 10, '20' => 20, '50' => 50, '100' => 100], 'data-redirect' => $path.'index/', 'class' => 'form-control select-redirection']]
          ];
  
          $leftLinks = array_merge($leftLinksDefaults, $leftLinks);
          $rightLinks = array_merge($rightLinksDefaults, $rightLinks);

          $format = function($element){

              $html = '';
              $i = '';
              $tag = '';

              if(array_key_exists('li', $element)){

                   if(array_key_exists('tag', $element)){

                        if(array_key_exists('i', $element)){
                             $i = $this->Html->tag('i', '', $element['i']).' ';
                        }

                        $type = Hash::get($element['tag'], 'type');

                        if($type == 'select'){
                             $tag = $this->Form->input('limite', $element['tag']);
                        }
                        else{
                             $tag = $this->Html->tag(Hash::get($element['tag'], 'type'), $i.Hash::get($element['tag'], 'text'), $element['tag']).' ';
                        }
                   }

                   $html = $this->Html->tag('li', $tag, $element['li']);
              }

              return $html;
          };
         
          $string = '';
          $string .= '
          <div style="padding:0px;" class="col-md-12">

               <div class="btn-group pull-left">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <i class="fa fa-cog"></i> Acciones <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                         '.implode("", array_map($format, $leftLinks)).'
                    </ul>
               </div>

               <div class="btn-group pull-right">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <span class="caret"></span> Opciones <i class="fa fa-wrench"></i>
                    </button>
                    <ul class="dropdown-menu">
                         '.implode("", array_map($format, $rightLinks)).'
                    </ul>
               </div>
          </div>';
          $string .= '<div class="clearfix"></div>';

          return $string;
     }

     public function controlOrders($fields)
     {
          $string = '';
 
          if(is_array($fields))
          {
               $sortDir = $this->Paginator->sortDir();               
               $sortKey = $this->Paginator->sortKey(); 
   
               foreach($fields AS $key => $title)
               {
                    $class = 'btn-default';
                    $icon = '<i class="fa fa-arrow-down"></i>';
                    if($sortKey == $key)
                    {
                         $class = ' btn-primary';
                         if($sortDir == 'desc')
                         {
                              $icon = '<i class="fa fa-arrow-up"></i>';
                         }
                         else
                         {
                              $icon = '<i class="fa fa-arrow-down"></i>';
                         }
                    }

                    $string .= $this->Paginator->sort($key, $icon.' '.$title, array('title' => 'Ordenar por: '.$title, 'class' => 'btn btn-sm '.$class, 'escape' => false))." ";
               }
          }

          return $string;
     }
 
     public static function formatFilter($filter)
     {
          $parts = explode("_", $filter);
          //unset($parts[0]); 
          $filter = implode(" ", $parts);
          return $filter;
     }

     public static function systemImage($file, $upload_path, $upload_image)
     {
          App::uses('UploadComponent', 'Controller/Component');

          $image = "";
          $file = trim($file);
          $upload_path = Router::url(trim($upload_path));
          $upload_image = Router::url(trim($upload_image));
          $extension = UploadComponent::getFormatedExtension($file);

          switch($extension)
          {
               case 'jpg':
               case 'png':
               case 'jpeg':
               case 'bmp':
               case 'gif':
                    $image = $upload_path.'/'.$file;
               break;
               case 'pdf': 
                    $image = $upload_image.'/upload_pdf.png';
               break;
               case 'doc': 
               case 'docx': 
                    $image = $upload_image.'/upload_word.png';
               break;
               case 'xls': 
               case 'xlsx': 
                    $image = $upload_image.'/upload_excel.png';
               break;
               case 'ppt': 
               case 'pptx': 
                    $image = $upload_image.'/upload_powerpoint.png';
               break;
               case 'mp4': 
               case 'avi': 
               case 'mpeg': 
               case 'mkv': 
                    $image = $upload_image.'/upload_video.png';
               break;
               case 'mp3': 
               case 'wav': 
               case 'ogg': 
                    $image = $upload_image.'/upload_audio.png';
               break;
               case 'zip': 
               case 'rar': 
               case '7zip': 
               case 'gz': 
                    $image = $upload_image.'/upload_compress.png';
               break;
               default:
                    $image = $upload_image.'/upload_cancel.png';
               break;
          }

          return $image;
     }

}

?>