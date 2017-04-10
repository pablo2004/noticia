<?php

 /*
   $i = new Image();

   $i->Open('emule.png');
   $i->Convert("emule");
   unlink('emule.png');

   $i->Open('emule.jpg');
   $i->Resize("emule", 100, 100);
   $i->Resize("thumb_emule", 50, 50);
  */

 class ImageComponent extends Component
 {

      private $gd_info = array();
      private $im = false;
      private $info = array();

      public function ImageComponent()
      {
           
      }

      function Convert($fname, $type = 'jpg', $quality = null, $destination_folder = '.')
      {
           if (is_resource($this->im))
           {
                return $this->Transform($fname, $this->info['width'], $this->info['height'], $type, $quality, $destination_folder);
           }

           return false;
      }

      function GD_Info($flag = null)
      {
           if (extension_loaded('gd'))
           {
                $result = array();

                if (empty($this->gd_info))
                {
                     $this->gd_info = gd_info();

                     $this->gd_info['GD Bundled'] = true;

                     if (strpos(strtolower($this->gd_info['GD Version']), 'bundled') === false)
                     {
                          $this->gd_info['GD Bundled'] = false;
                     }

                     preg_match('([\d\.]+)', $this->gd_info['GD Version'], $match);

                     $this->gd_info['GD Version'] = $match[0];
                }

                if (!empty($flag))
                {
                     if (array_key_exists($flag, $this->gd_info))
                     {
                          $result = $this->gd_info[$flag];
                     }
                     else
                     {
                          $result = false;
                     }
                }

                return $result;
           }

           return false;
      }

      function Info($flag = null, $image = null)
      {
           if (!empty($image))
           {
                if (is_file($image))
                {
                     $image_info = getimagesize($image);

                     if ($image_info !== false)
                     {
                          $result = array();

                          list($result['width'], $result['height'], $result['type']) = $image_info;

                          $image_types = array('gif', 'jpg', 'png', 'swf', 'psd', 'bmp', 'tif', 'tif', 'jpc', 'jp2', 'jpx', 'jb2', 'swc', 'iff', 'wbmp', 'xbm');

                          if (($result['type'] >= 1) && ($result['type'] <= 16))
                          {
                               $result['type'] = $image_types[$result['type'] - 1];
                          }

                          if (!empty($flag))
                          {
                               if (array_key_exists($flag, $result))
                               {
                                    $result = $result[$flag];
                               }
                               else
                               {
                                    $result = false;
                               }
                          }

                          return $result;
                     }
                }

                return false;
           }

           if (is_resource($this->im))
           {
                $result = $this->info;

                if (!empty($flag))
                {
                     if (array_key_exists($flag, $this->info))
                     {
                          $result = $this->info[$flag];
                     }
                     else
                     {
                          $result = false;
                     }
                }

                return $result;
           }

           return false;
      }

      function Open($image)
      {
           if (!extension_loaded('gd'))
           {
                return false;
           }

           if (is_resource($this->im))
           {
                imagedestroy($this->im);
           }

           $this->im = false;
           $this->info = array();

           if (is_file($image))
           {
                $image_info = getimagesize($image);

                if ($image_info !== false)
                {
                     list($this->info['width'], $this->info['height'], $this->info['type']) = $image_info;

                     $image_types = array('gif', 'jpg', 'png', 'swf', 'psd', 'bmp', 'tif', 'tif', 'jpc', 'jp2', 'jpx', 'jb2', 'swc', 'iff', 'wbmp', 'xbm');

                     if (($this->info['type'] >= 1) && ($this->info['type'] <= 16))
                     {
                          $this->info['type'] = $image_types[$this->info['type'] - 1];
                     }

                     $this->info['hash'] = sha1_file($image);

                     if ($this->info['type'] == 'gif')
                     {
                          $this->im = imagecreatefromgif($image);
                     }
                     else if ($this->info['type'] == 'jpg')
                     {
                          $this->im = imagecreatefromjpeg($image);
                     }
                     else if ($this->info['type'] == 'png')
                     {
                          $this->im = imagecreatefrompng($image);

                          /* $transparent = imagecolorallocate($this->im, 255, 255, 255); 
                            imagefill($this->im, 0, 0, $transparent);
                            imagecolortransparent($this->im, $transparent); */

                          ################################### 

                          imagecolortransparent($this->im);
                     }

                     if (!is_resource($this->im))
                     {
                          $this->im = false;
                          $this->info = array();
                     }
                }
           }

           return $this;
      }

      function Path($path)
      {
           if (file_exists($path))
           {
                $path = str_replace('\\', '/', realpath($path));

                if (is_dir($path))
                {
                     $path .= '/';
                }

                return $path;
           }

           return false;
      }

      function Resize($fname, $width = null, $height = null, $quality = null, $destination_folder = '.')
      {
           if (is_resource($this->im))
           {
                return $this->Transform($fname, $width, $height, $this->info['type'], $quality, $destination_folder);
           }

           return false;
      }

      function Transform($fname, $width = null, $height = null, $type = null, $quality = null, $destination_folder = '.')
      {
           if (is_resource($this->im))
           {
                if (!in_array($this->info['type'], array('gif', 'jpg', 'png')))
                {
                     return false;
                }

                $width = intval($width);
                $height = intval($height);

                if ((empty($width)) && (empty($height)))
                {
                     return false;
                }

                if (empty($width))
                {
                     $width = round($height * $this->info['width'] / $this->info['height']);
                }

                if (empty($height))
                {
                     $height = round($width * $this->info['height'] / $this->info['width']);
                }

                $type = strtolower($type);

                if ((empty($type)) || (!in_array($type, array('gif', 'jpg', 'png'))))
                {
                     $type = $this->info['type'];
                }

                if (is_null($quality))
                {
                     $quality = 100;
                }

                $quality = intval($quality);

                if ($type == 'jpg')
                {
                     if (($quality < 0) || ($quality > 100))
                     {
                          $quality = 100;
                     }
                }
                else
                {
                     $quality = 100;
                }

                $destination_folder = $this->Path($destination_folder);

                if (!is_dir($destination_folder))
                {
                     return false;
                }

                //$destination_image = sha1($this->info['hash'] . $width . $height . $quality) . '.' . $type; 
                $destination_image = $fname . '.' . $type;

                /* if (file_exists($destination_folder . $destination_image)) 
                  {
                  return $destination_image;
                  } */

                if (version_compare($this->GD_Info('GD Version'), '2.0.28', '>='))
                {
                     $image = imagecreatetruecolor($width, $height);

                     if ($this->GD_Info('GD Bundled') === true)
                     {
                          imageantialias($image, true);
                     }

                     imagecopyresampled($image, $this->im, 0, 0, 0, 0, $width, $height, $this->info['width'], $this->info['height']);
                }
                else
                {
                     $image = imagecreate($width, $height);

                     if ($this->GD_Info('GD Bundled') === true)
                     {
                          imageantialias($image, true);
                     }

                     imagecopyresized($image, $this->im, 0, 0, 0, 0, $width, $height, $this->info['width'], $this->info['height']);
                }

                if ($type == 'gif')
                {
                     if (imagegif($image, $destination_folder . $destination_image) !== false)
                     {
                          imagedestroy($image);

                          return $destination_image;
                     }
                }
                else if ($type == 'jpg')
                {
                     imageinterlace($image, true);

                     if (imagejpeg($image, $destination_folder . $destination_image, $quality) !== false)
                     {
                          imagedestroy($image);

                          return $destination_image;
                     }
                }
                else if ($type == 'png')
                {
                     if (imagepng($image, $destination_folder . $destination_image) !== false)
                     {
                          imagedestroy($image);

                          return $destination_image;
                     }
                }

                return false;
           }

           return false;
      }

 }

?>