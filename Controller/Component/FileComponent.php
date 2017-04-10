<?php

class FileComponent extends Component
{

     public function imageUpload($options)
     {

          App::uses('UploadComponent', 'Controller/Component');
          App::uses('ImageComponent', 'Controller/Component');

          $upload = new UploadComponent();
          $image = new ImageComponent();

          $data = "";
          $image_name = "";
          $error = "";

          $defaults = [
               'uploadPath' => '',
               'maxSize' => '0',
               'acceptedFiles' => '',
               'original' => 1,
               'imageResize' => '',
               'imageThumbnail' => ''
          ];

          $file = (isset($file)) ? $file : "";

          $options = array_merge($defaults, $_POST);
          extract($options);
          
          $id = (empty($name)) ? sha1(uniqid()) : $name;
          $uploadPath = WWW_ROOT . $uploadPath;
 
          $valids = array_map("trim", explode(",", $acceptedFiles));

          $upload->setPath($uploadPath);
          $upload->setName($id);
          $upload->setFile($file);
          $upload->setSupportedExtensions($valids);

          $upload->isSupportedFile();
          $upload->maxSize($maxSize);

          if(!empty($file))
          {
               if (!$upload->errorExists())
               {
                    if($upload->uploadFile())
                    { 
                         $file_uploaded = $upload->getFullPath();
                         $extension = $upload->getExtension();

                         $image->Open($file_uploaded);

                         chmod($file_uploaded, 0777);

                         // Convert to jpg
                         if ($original == 0)
                         {
                              if (strcmp($extension, "jpg") != 0)
                              {
                                   $image->Convert($id, 'jpg', 100, $uploadPath . "/");
                                   unlink($file_uploaded);

                                   $extension = "jpg";
                                   chmod("$uploadPath/$id.$extension", 0777);
                              }
                         }

                         // Resize
                         if (isset($imageResize))
                         {
                              if (self::isDimention($imageResize))
                              {

                                   $change = false;
                                   list($width, $height) = getimagesize("$uploadPath/$id.$extension");
                                   list($res_width, $res_height) = explode("x", $imageResize);

                                   while ($width > $res_width || $height > $res_height)
                                   {
                                        $width = $width * 0.9;
                                        $height = $height * 0.9;
                                        $change = true;
                                   }

                                   if ($change)
                                   {
                                        $image->Open("$uploadPath/$id.$extension");
                                        $image->Resize($id, $width, $height, 100, $uploadPath . "/");
                                        chmod("$path/$id.$extension", 0777);
                                   }
                              }
                         }

                         // Get Thumbnail
                         if (isset($imageThumbnail))
                         {
                              if (self::isDimention($imageThumbnail))
                              {
                                   $thumb = explode("x", $imageThumbnail);
                                   $image->Open("$uploadPath/$id.$extension");
                                   $image->Resize("thumb_" . $id, $thumb[0], $thumb[1], 100, $uploadPath . "/");
                                   chmod($uploadPath . "/thumb_" . $id . ".$extension", 0777);
                              }
                         }

                         $image_name = $id . '.' . $extension;
                    }
                    else
                    {
                         $error = 'Error: No se subio la imagen.';
                    }
               }
               else
               {
                    $error = $upload->getError();
               }

          }

          $data = '{ "data": "'.$image_name.'", "error": "'.$error.'" }';

          return $data;
     }

     public function fileUpload($options)
     {

          App::uses('UploadComponent', 'Controller/Component');

          $upload = new UploadComponent();

          $data = "";
          $file_name = "";
          $error = "";

          $defaults = [
               'uploadPath' => '',
               'maxSize' => '0',
               'acceptedFiles' => ''
          ];

          $file = (isset($file)) ? $file : "";

          $options = array_merge($defaults, $_POST);
          extract($options);
          
          $id = (empty($name)) ? sha1(uniqid()) : $name;
          $uploadPath = WWW_ROOT . $uploadPath;
 
          $valids = array_map("trim", explode(",", $acceptedFiles));

          $upload->setPath($uploadPath);
          $upload->setName($id);
          $upload->setFile($file);
          $upload->setSupportedExtensions($valids);

          $upload->isSupportedFile();
          $upload->maxSize($maxSize);

          if(!empty($file))
          {
               if (!$upload->errorExists())
               {
                    if($upload->uploadFile())
                    { 
                         $file_uploaded = $upload->getFullPath();
                         $extension = $upload->getExtension();

                         chmod($file_uploaded, 0777);

                         $file_name = $id . '.' . $extension;
                    }
                    else
                    {
                         $error = 'Error: No se subio la imagen.';
                    }
               }
               else
               {
                    $error = $upload->getError();
               }

          }

          $data = '{ "data": "'.$file_name.'", "error": "'.$error.'" }';

          return $data;
     }

     public static function isDimention($string)
     {
          return preg_match("/^([0-9]{1,4})+x([0-9]{1,4})$/i", trim($string));
     }

}

?>