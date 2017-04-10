<?php

class InputHelper extends AppHelper 
{
     
     public $helpers = array('Html', 'Form');
     private $name = '';
     private $validate = '{}';
     private $controllerPath = '';
     private $data = [];
     
     public function __construct(View $View, $settings = array())
     {
          parent::__construct($View, $settings);
     }

     public function setName($name)
     {
          $name = trim($name);
          if(!empty($name))
          {
               $this->name = $name;
          }
     }

     public function getName()
     {
          return $this->name;
     }

     public function setData($data)
     {
          if(is_array($data)){
               $this->data = $data;
          }
     }

     public function getData()
     {
          return $this->data;
     }

     public function setValidate($validate)
     {
          $validate = trim($validate);
          if(!empty($validate))
          {
               $this->validate = $validate;
          }
     }

     public function getValidate()
     {
          return $this->validate;
     }
     
     public function setControllerPath($path)
     {
          $path = trim($path);
          if(!empty($path))
          {
               $this->controllerPath = $path;
          }
     }

     public function getControllerPath()
     {
          return $this->controllerPath;
     }

     public function create($name, $options = array())
     {
          $name = trim($name);
          $this->setName($name);

          if(!array_key_exists("data-controller-path", $options))
          {
               $options['data-controller-path'] = Router::url($this->getControllerPath());
          }

          if(!array_key_exists("data-validate", $options))
          {
               $options['data-validate'] = $this->getValidate();
          }
	  
	     if(!array_key_exists("data-model", $options))
          {
	          $options['data-model'] = $name;
	     }
	  
          if(!array_key_exists("class", $options))
          {
	          $options['class'] = "form-horizontal";
          }

          if(!array_key_exists("onsubmit", $options))
          {
               $options['onsubmit'] = "return false;";
          }
	  
          return "\n".$this->Form->create($name, $options)."\n";
     }
     
     public function element($name, $label = "", $type = "", $class = "", $options = array())
     {
          $element = "";
	     $name = trim($name);
	     $label = trim($label);
	     $class = trim($class);
	     $type = trim($type);
          $class = (empty($class)) ? 'input-xlarge' : $class;
          $data = $this->getData();

	     if(empty($label))
	     {
	          $label = Inflector::humanize($name);
	     }
	  
          if(!empty($type))
          {
               $options['type'] = $type;
          }
	  
          if(!array_key_exists("div", $options))
          {
               $options['div'] = false;
               $options['label'] = false;
          }

          if(!array_key_exists("placeholder", $options))
          {
               $options['placeholder'] = $label;
          }

          if(!array_key_exists("div-class", $options))
          {
               $options['div-class'] = "";
          }

          if(sizeof($data) > 0){
               if(array_key_exists($name, $data)){
                    $options['value'] = $data[$name];
               }
          }
	  
          $options['data-error'] = '';
          $camelize = (array_key_exists('id', $options)) ? $options['id'] : $this->Form->defaultModel.Inflector::camelize($name);

          if(strcasecmp($type, "hidden") === 0)
          {
	          $element .= "\n".$this->Form->input($name, $options)."\n";
          }
          else
          {
               if(strcasecmp($type, "checkbox") == 0){
                    $options['class'] = $class;
                    $input = '<label class="btn btn-warning btn-sm" for="'.$camelize.'">'.$this->Form->input($name, $options).'</label>';
               }
               else{
                    $options['class'] = $class." form-control";
                    $input = $this->Form->input($name, $options);
               }

	          $element .= "\n".$this->Html->tag("label", $label, array('for' => $camelize, 'class' => 'col-lg-3 control-label', 'style' => 'text-align:left;'));
               $element .= "\n".$this->Html->div("col-lg-9", "\n".$input."\n")."\n";
	          $element = $this->Html->div("form-group ".$options['div-class'], $element, array('id' => 'Div'.$camelize))."\n";
          }
	  
          return $element;
     }
     
     public function end()
     {
          $element = "";
          $element .= $this->Form->end()."\n";
	     return $element;
     }
     
     public function button($value, $icon = "", $class = "btn", $type = "button", $options = array())
     {
          $button = "";
          $value = trim($value);
	     $icon = trim($icon);
          $class = trim($class);
          $type = trim($type);
	  
          if(!empty($class))
          {
               $options['class'] = $class;
          }
	  
          if(!empty($type))
          {
               $options['type'] = $type;
          }

          if(!empty($icon))
          {
               $value = '<i class="'.$icon.'"></i> '.$value;
          }
	  
          $button = $this->Form->button($value, $options)."\n";
          return $button;
     }
     
     public function buttonRequest($value, $form, $type, $message, $options = array())
     {
          $button = "";
          $form = trim($form);
          $type = trim($type);
          $message = trim($message);
	  
          $options['data-form'] = $form;
	     $options['data-type'] = $type;
	     $options['data-message'] = $message;
	  
          $button = $this->button($value, "fa fa-check", "btn btn-success ajax-submit", "submit", $options);
          return $button;
     }
     
     public function buttonReset($value, $form)
     {
          $button = "";
	     $form = trim($form);
	  
          $options['data-form'] = $form;
	  
          $button = $this->button($value, "fa fa-eraser", "btn ajax-reset", "button", $options);
          return $button;
     }

     public function buttonSearch($value, $form)
     {
          $button = "";
	     $form = trim($form);
	  
	     $options['onclick'] = "$('$form').submit();";
	  
	     $button = $this->button($value, "fa fa-search", "btn btn-info", "submit", $options);
	     return $button;
     }

     public function buttonLink($value, $path = '#', $class = '', $icon = '', $options = array())
     {
          $value = trim($value);
	     $path = trim($path);
	     $icon = trim($icon);
	     $class = trim($class);
          $path = (empty($path)) ? $this->getControllerPath() : $path;

          if(!empty($icon))
          {
               $value = '<i class="'.$icon.'"></i> '.$value;
          }

          if(!empty($class))
          {
               $options['class'] = $class;
          }
          
          $options['href'] = Router::url($path); 

          $button = $this->Html->tag('a', $value, $options)."\n";
          return $button;
     }
     
     public function buttonList($label = 'Volver', $path = '')
     {
          $HTTP_REFERER = Hash::get($_SERVER, 'HTTP_REFERER');
          $REQUEST_URI = Hash::get($_SERVER, 'REQUEST_SCHEME')."://".Hash::get($_SERVER, 'HTTP_HOST').Hash::get($_SERVER, 'REQUEST_URI');
          if(empty($path))
          {
               $HTTP_REFERER = (strcasecmp($HTTP_REFERER, $REQUEST_URI) === 0) ? '' : $HTTP_REFERER; 
               $path = (!empty($HTTP_REFERER)) ? $HTTP_REFERER : '';
          }
          $button = $this->buttonLink($label, $path, 'btn btn-primary', 'fa fa-arrow-left', array())."\n";
          return $button;
     }
     
     public function buttonDelete($value, $url, $redirect, $options = array())
     {
          $value = trim($value);
	     $url = trim($url);
	     $redirect = trim($redirect);

	     $options['data-url'] = $url;
	     $options['data-redirect'] = $redirect;
	     $options['class'] = "btn btn-danger ajax-delete";
	  
	     $button = $this->buttonLink($value, "#", 'btn btn-danger ajax-delete', 'fa fa-times', $options)."\n";
	     return $button;
     }


     public function uploadElement($name, $options = [])
     {
          App::uses("UploadComponent", "Controller/Component");
          $defaultOptions = [
               'id' => $this->Form->defaultModel.Inflector::camelize($name),
               'code' => uniqid(),
               'class' => '',
               'label' => 'Cargar Archivo',
               'uploadPath' => '',
               'downloadPath' => '',
               'removePath' => '',
               'acceptedFiles' => 'jpeg,png,bmp,jpg,gif',
               'original' => 1,
               'maxSize' => '1048576',
               'element' => true,
               'imageResize' => '',
               'imageThumbnail' => '',
               'postUrl' => '',
               'iconPath' => Router::url('/img/'),
               'viewImage' => ''
          ];

          $options = array_merge($defaultOptions, $options);
          $options['postUrl'] = Router::url($options['postUrl']);
          $options['viewImage'] = Router::url($options['uploadPath']);
          $options['removePath'] = Router::url($options['removePath']);
          $options['downloadPath'] = Router::url($options['downloadPath']);
          extract($options);

          $html = '';
          $model = $this->getName();
          $file = (isset($this->request->data[$model][$name])) ? $this->request->data[$model][$name] : "" ;
          $image = DomHelper::systemImage($file, $uploadPath, '/img');

          if($element){
               $html .= $this->element($name, "", "hidden"); 
          }

          if(!empty($removePath)){
               $remove = '<i class="fa fa-times"></i> <a data-code="'.$code.'" id="upload-remove-'.$code.'" href="#" data-url="'.$removePath."/".$file.'" data-path="'.$removePath.'" class="upload-remove">Borrar</a>';
          }

          if(!empty($downloadPath)){
               $download = '<i class="fa fa-download"></i> <a id="upload-download-'.$code.'" href="#" class="link-var" data-path="'.$downloadPath.'" data-var="'.$file.'">Descargar</a><br />';
          }

          $html .= '<div class="panel-body">';
          $html .= '<input data-code="'.$code.'" class="upload-input-config" type="hidden" value="'.base64_encode(json_encode($options)).'" />';
          $html .= '<img id="upload-preview-'.$code.'" style="width:64px;height:64px;margin-right:8px;" title="Archivo Cargado" class="pull-left system-tooltip img-thumbnail" src="'.$image.'" />';
          $html .= '<i class="fa fa-cloud-upload"></i> <a style="cursor:pointer;" onclick="$(\'#upload-dialog-'.$code.'\').dialog(\'open\');"><b>'.$label.'</b></a><br />';
          $html .= $remove.' '.$download;
          $html .= '<i class="fa fa-check"></i> Archivos Permitidos: '.$acceptedFiles.'<br />';
          $html .= '<i class="fa fa-cube"></i> Peso Maximo: '.UploadComponent::formatBytes($maxSize).'<br />';
          $html .= '<div class="clearfix"></div>';
          $html .= '</div>';

          return $this->Html->div("panel panel-default ".$class, $html, array('id' => 'panel-'.$code));
     }

}

?>
