<?php

class MenuComponent extends Component
{
 
     const IZQUIERDA = 1;
     const DERECHA = 2;
     public $menu = [1 => '', 2 => ''];
     public $subs = [];


     public function addSubMenu($sub)
     {
          if(is_array($sub))
          {
               $default = array(
                    'padre' => '',
                    'clase' => '',
                    'icono' => 'fa fa-chevron-right',
                    'enlace' => '',
                    'nombre' => '',
                    'data-controller' => '',
                    'data-action' => '',
                    'data-on-denied' => 'hide',
                    'display' => 1
               );

               $sub = array_merge($default, $sub);
               array_push($this->subs, $sub);
          }
     }
     

     public function startup(Controller $controller)
     {

          $tipo_id = 1;
          $padre_id = 0;

          $conditions = array();
          $conditions['Nav.tipo_id ='] = $tipo_id;
          $conditions['Nav.padre_id ='] = $padre_id;

          $Nav = AppController::_newInstance("Nav");
          $navs = $Nav->find("all", array('order' => 'Nav.orden ASC', 'conditions' => $conditions));
          $html = '';
          $subs = $this->subs;
          
          foreach($navs AS $nav)
          {
               if($tipo_id == 1)
               {
                    if(AppController::checkRol(rol('id'), $nav['Nav']['rol_permitido']))
                    {

                         $size = sizeof($nav['NavLink']);
                         $collapse = ($size > 0) ? 'system-collapse' : '';
                         $html .= $this->replaceNavHtml('<a id="linkId'.$nav['Nav']['id'].'" data-target=".linkId'.$nav['Nav']['id'].'" class="'.$collapse.' link '.$nav['Nav']['clase'].'" href="'.Router::url($nav['Nav']['enlace']).'"><i class="'.$nav['Nav']['icono'].'"></i>  '.$nav['Nav']['nombre'].'</a>');
                          
                         if($size > 0){    

                              $html .= '<div data-id="'.$nav['Nav']['id'].'" class="menu-box linkId'.$nav['Nav']['id'].'">';
                              $nav['NavLink'] = Hash::sort($nav['NavLink'], '{n}.orden', 'asc');
  
                              foreach($nav['NavLink'] AS $child){

                                   if(AppController::checkRol(rol('id'), $child['rol_permitido'])){

                                        $html .= $this->replaceNavHtml('<a id="linkId'.$child['id'].'" class="sub-link '.$child['clase'].'" href="'.Router::url($child['enlace']).'"><i class="'.$child['icono'].'"></i>  '.$child['nombre'].'</a>');
                                        $navSubs = array_filter($subs, function($sub) use ($child){
                                             return $child['enlace'] == $sub['padre'];
                                        });
                         
                                        if(sizeof($navSubs) > 0){
                                             $html .= '<div data-id="'.$child['enlace'].'" class="linkId'.$child['id'].'">';
                                             foreach($navSubs AS $sub){
                                                  $display = ($sub['display'] == 1) ? 'menu-display ' : '';
                                                  $html .= $this->replaceNavHtml('<a data-on-denied="'.$sub['data-on-denied'].'" data-controller="'.$sub['data-action'].'" data-action="'.$sub['data-action'].'" class="'.$display.'sub-sub-link '.$sub['clase'].'" href="'.Router::url($sub['enlace']).'"><i class="'.$sub['icono'].'"></i>  '.$sub['nombre'].'</a>');
                                             }
                                             $html .= '</div>';
                                        }

                                   }
                              }
                              $html .= '</div>';
                         }
                    }  
               }
          }

          $this->menu[$nav['Nav']['lado_menu']] = $html;

     }

     public function getMenu($menu = 1)
     {
          $menu = intval($menu);
          return Hash::get($this->menu, $menu);	
     }
       
     public function replaceNavHtml($nav)
     {
          preg_match_all("/\{([a-z0-9\_]{1,})\}/", $nav, $matches);

          if(isset($matches[1]))
          {
               foreach($matches[1] AS $key => $match)
               {
                    $replacement = call_user_func('user', $matches[1][$key]);
                    $replacement = (empty($replacement)) ? Hash::get($_GET, $matches[1][$key]) : $replacement;
                    $nav = str_replace($matches[0][$key], $replacement, $nav);
               }
          }

          return $nav;
     }

}

?>
