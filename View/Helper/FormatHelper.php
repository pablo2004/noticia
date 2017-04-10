<?php


class FormatHelper extends AppHelper
{

     public function formatCallback($record, $callback)
     {
          $return = "";

          if(is_array($record) && is_callable($callback))
          {
               $return = call_user_func($callback, $record);
          }

          return $return;
     }

     public function formatTemplateCallback($records, $callback)
     {
          $result = "";   
      
          if(is_array($records))
          {
               foreach($records AS $record)
               {
                    $result .= $this->formatCallback($record, $callback);
               }
          }

          return $result;
     }

}

?>