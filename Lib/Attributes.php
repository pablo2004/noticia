<?php

trait Attributes
{

    private $attributes = array();

    public function getAttr($index)
    {
        $value = "";
        $index = trim($index);

        if(!empty($index))
        {
            if($this->attrExists($index))
            {
                $value = $this->attributes[$index];
            }
        }

        return $value;
    }

    public function setAttr($index, $value)
    {
        $index = trim($index);

        if(!empty($index))
        {
            $this->attributes[$index] = $value;
        }
    }

    public function attrExists($index)
    {
         $index = trim($index);
         return array_key_exists($index, $this->getAttrs());
    }

    public function addToAttr($attr, $value, $index = null)
    {
         $attr = trim($attr);

         if($this->attrExists($attr))
         {
              $self = $this->getAttr($attr);
              if(is_array($self))
              {
                   $size = sizeof($self);
                   if($index == null)
                   {
                        array_push($self, $value);
                   }
                   else
                   {
                        $self[$index] = $value;
                   }
                   $this->setAttr($attr, $self);
              }
         }
         else
         {
              $index = ($index == null) ? 0 : $index;
              $value = array($index => $value);
              $this->setAttr($attr, $value);
         }
    }

    public function setAttrs($attributes, $replace = true)
    {
        if(is_array($attributes))
        {
             if($replace)
             {
                  $this->attributes = $attributes;
             }
             else
             {
                  $attributes = array_merge($this->getAttrs(), $attributes);
                  $this->attributes = $attributes;
             }
        }
    }

    public function getAttrs($indexes = null)
    {
        $attributes = array();

        if(is_array($indexes))
        {
             foreach($indexes AS $index)
             {
                 $attributes[$index] = $this->getAttr($index);
             }
        }
        else
        {
            $attributes = $this->attributes;
        }

        return $attributes;
    }

}


?>