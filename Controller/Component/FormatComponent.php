<?php

class FormatComponent extends Component
{

     public static function getCatalog($name, $conditions = [], $join = null, $order = [], $recursive = -1)
     {
          $name = trim($name);
          $order = array_merge(array("Catalogo.valor" => "ASC"), $order);
          $conditions = array_merge(array("Catalogo.activo =" => 1, 'Catalogo.nombre =' => $name), $conditions);
          $fields = array('Catalogo.valor', 'Catalogo.etiqueta');

          $Catalogo = AppController::_newInstance("Catalogo");
          $catalog = $Catalogo->find("list", array('conditions' => $conditions, 'order' => $order, 'fields' => $fields, 'recursive' => $recursive));
        
          if(is_array($join)){
               foreach ($join AS $key => $value) {
                    $catalog[$key] = $value;
               }
               ksort($catalog);
          }
         
          return $catalog;
     }

     public static function translateDate($date)
     {
          $original = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
          $spanish = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
          return str_ireplace($original, $spanish, $date);
     }

     public static function defaultDate($date)
     {
          $date = strtotime($date);

          $day = date("d", $date);
          $month = self::translateDate(date("F", $date));
          $year = date("Y", $date);
          $date = "$day de $month del $year";

          return $date;
     }

     public static function dateToAgeMedical($date)
     {
          $date = strtotime($date);
          $now = time();
          $seconds = $now - $date;
          $days = ((($seconds / 60) / 60) / 24);
          $years = $days / 365;
          $residual = $days % 365;
          $weeks = intval($days / 7);

          $yearsInt = intval($years);
          $monthsInt = intval($residual / 30);

          $return = "";

          if($yearsInt >= 0){
               $return .= '<strong>'.$yearsInt.'</strong> A&ntilde;os';
          }
          if($monthsInt >= 0){
               $return .= ' <strong>'.$monthsInt.'</strong> meses';
          }


          $content = '<b>'.$weeks.'</b> semanas</span><br><b>'.intval($days).'</b> dias';
          
          return '<a href="#" data-placement="top" data-html="true" data-content="'.$content.'" class="help-util">'.$return.'</a>';
     }

}

?>
