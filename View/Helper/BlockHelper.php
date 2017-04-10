<?php

class BlockHelper extends AppHelper 
{
     
     public function setMainBlock($title, $content, $icon = 'fa fa-folder-o')
     {
	      $html = '';
	      $html .= '<div class="panel panel-primary margin-block">';
          $html .= '     <div class="panel-heading"><i class="'.$icon.'"></i> '.$title.'</div>';
          $html .= '     <div class="panel-body">'.$content.'</div>';              
	      $html .= '</div>';
	  
	      return $html;
     }
     
     public function setBlock($title, $items = array())
     {
	      $data = '';
	      if(is_array($items))
	      {
	           foreach($items AS $item)
	           {
	                $data .= '<li class="'.$item['class'].'"><a href="'.Router::url($item['url']).'"><i class="'.$item['icon'].'"></i> '.$item['label'].'</a></li>';
	           }
	      }
	  
	      $html = '';
	      $html .= '<div class="panel panel-default">';
	      $html .= '<div class="panel-heading">'.$title.'</div>';
	      $html .= '<div class="panel-body">';
          $html .= '     <ul class="list-group list-group-flush">';
          $html .= '          '.$data;  
          $html .= '     </ul>';
          $html .= '</div>';
	      $html .= '</div>';
	  
	      return $html;     
     }
     
     public function setBody($sidebar1 = "", $content = "", $sidebar2 = "")
     {
	      $html = '';

	      $main_size = 12;
	      $block_size = 3; 

	      if(!empty($sidebar1))
	      {
               $main_size = $main_size - $block_size;
	      }

	      if(!empty($sidebar2))
	      {
               $main_size = $main_size - $block_size;
	      }

	      if(!empty($sidebar1))
	      {
	           $html .= '<div id="block-sidebar1" class="side-bar side-bar-left col-lg-3 col-md-3 col-sm-3 col-xs-8">';
	           $html .= '<div class="side-bar-name"> Men&uacute;</div>'.$sidebar1;
	           $html .= '</div>';
	      }
	  
	      $html .= '<div id="block-main" class="pull-right col-lg-'.$main_size.' col-md-'.$main_size.' col-sm-'.$main_size.' col-xs-12">';
	      $html .= '     '.$content;
	      $html .= '</div>';

	      if(!empty($sidebar2))
	      {
	           $html .= '<div id="block-sidebar2" class="side-bar side-bar-right col-sm-'.$block_size.' col-md-'.$block_size.'">';
	           $html .= '     '.$sidebar2;
	           $html .= '</div>';
	      }

	      return $html;
     }
     
}