<?php

 /* //////////////////////////////////////////////////////////////////
   // Autor: Pablo Ramirez - bugs@celwebs.com
   // Under License GNU/GPLv3
   // http://www.gnu.org/copyleft/gpl.html
   // Version 2.0 - Made in Mexico 2011
  *///////////////////////////////////////////////////////////////////

 class UploadComponent extends Component
 {
      /* Note: Form must have: enctype="multipart/form-data" */

      private $file_temporal = "";
      private $file_name = "";
      private $file_path = "";
      private $file_size = 0;
      private $file_type = "";
      private $file_extension = "";
      private $file_error = "";
      private $file_supported_extensions = array();
      private $file_full_path = "";
      private $file_path_chmod = 0777;

      /* SETTERS & GETTERS */

      public function UploadComponent()
      {
           
      }

      public function setTemporal($temporal)
      {
           $temporal = trim($temporal);
           if (!empty($temporal))
           {    
                $this->file_temporal = $temporal;
           }
      }

      public function getTemporal()
      {
           return $this->file_temporal;
      }

      public function setName($name)
      {
           $name = trim($name);
           if (!empty($name))
           {
                $this->file_name = $name;
           }
      }

      public function getName()
      {
           return $this->file_name;
      }

      public function setPath($path)
      {
           $path = trim($path);
           if (!empty($path))
           {
                $this->file_path = $path;
           }
      }

      public function getPath()
      {
           return $this->file_path;
      }

      public function setSize($size)
      {
           $size = intval($size);
           if ($size > 0)
           {
                $this->file_size = $size;
           }
      }

      public function getSize()
      {
           return $this->file_size;
      }

      public function setType($type)
      {
           $type = trim($type);
           if (!empty($type))
           {
                $this->file_type = $type;
           }
      }

      public function getType()
      {
           return $this->file_type;
      }

      public function setExtension($extension)
      {
           $extension = trim($extension);
           if (!empty($extension))
           {
                $this->file_extension = $extension;
           }
      }

      public function getExtension()
      {
           return $this->file_extension;
      }

      public function setError($error)
      {
           $error = trim($error);
           if (!empty($error))
           {
                $this->file_error = $error;
           }
      }

      public function getError()
      {
           return $this->file_error;
      }

      public function setSupportedExtensions($extensions)
      {
           if (is_array($extensions))
           {
                $this->file_supported_extensions = $extensions;
           }
           else
           {
                $this->file_supported_extensions = (array) $extensions;
           }
      }

      public function getSupportedExtensions()
      {
           return $this->file_supported_extensions;
      }

      public function setFullPath($path)
      {
           $path = trim($path);
           if (!empty($path))
           {
                $this->file_full_path = $path;
           }
      }

      public function getFullPath()
      {
           return $this->file_full_path;
      }

      public function setPathChmod($chmod)
      {
           $chmod = trim($chmod);
           if (is_numeric($chmod))
           {
                $this->file_path_chmod = $chmod;
           }
      }

      public function getPathChmod()
      {
           return $this->file_path_chmod;
      }

      /* OPERATIONS */

      public function errorExists()
      {
           $error = $this->getError();
           return (empty($error)) ? false : true;
      }

      public function maxSize($size_max)
      {
           $size_max = intval($size_max);
           $size = $this->getSize();

           if ($size > $size_max)
           {
                $this->setError("El peso del archivo es mayor a: " . UploadComponent::formatBytes($size_max));
           }
      }

      public function setFile($file)
      {
           if (is_array($file))
           {
                $extension = UploadComponent::getFormatedExtension($file['name']);
                $name = $this->getName();
                $name = (empty($name)) ? $file['name'] : $this->getName() . "." . $extension;

                $this->setName($name);
                $this->setTemporal($file['tmp_name']);
                $this->setSize($file['size']);
                $this->setType($file['type']);
                $this->setError($file['error']);
                $this->setExtension($extension);
                $this->setFullPath($this->getPath() . "/" . $this->getName());
           }
           else
           {
                $file = trim($file);
                if (array_key_exists($file, $_FILES))
                {   
                     $this->setFile($_FILES[$file]);
                }
           }
      }

      public function isSupportedFile()
      {
           $supported = false;
           $supporteds = $this->getSupportedExtensions();
           $extension = $this->getExtension();

           foreach ($supporteds AS $support)
           {
                if (strcasecmp($support, $extension) == 0)
                {
                     $supported = true;
                     break;
                }
           }

           if (!$supported)
           {
                $this->setError("Archivo no soportado, soportados: " . implode(",", $supporteds));
           }

           return $supported;
      }

      public function createFolder($folder, $recursive = false)
      {
           $created = false;
           $folder = trim($folder);
           $recursive = (bool) $recursive;

           if (!file_exists($folder))
           {
                if (@mkdir($folder, $this->getPathChmod(), $recursive))
                {
                     $created = true;
                }
           }
           else
           {
                $created = true;
           }

           return $created;
      }

      public function uploadFile()
      {
           $folder_exists = $this->createFolder($this->getPath(), true);
           $upload_file = false;

           if ($folder_exists)
           {
                if(move_uploaded_file($this->getTemporal(), $this->getFullPath()))
                {       
                     chmod($this->getFullPath(), $this->getPathChmod());
                     $upload_file = true;
                }
                else
                {
                     $this->setError("No se pudo Subir el Archivo.");
                }
           }
           else
           {
                $this->setError("No se encontro la ruta.");
           }

           return $upload_file;
      }

      public static function getFormatedExtension($file)
      {
           $extension = "";
           $file = trim($file);
           $parts = explode(".", $file);
           $size = sizeof($parts);

           if ($size > 0)
           {
                $extension = $parts[$size - 1];
           }

           return strtolower($extension);
      }

      public static function getFormatedName($file)
      {
           $name = "";
           $file = trim($file);
           $parts = explode("/", $file);
           $size = sizeof($parts);

           if ($size > 0)
           {
                $name = $parts[$size - 1];
                $name = str_replace("." . UploadComponent::getFormatedExtension($file), "", $name);
           }

           return $name;
      }

      public static function formatBytes($bytes)
      {
           if ($bytes < 1024)
                return $bytes . ' B';
           elseif ($bytes < 1048576)
                return round($bytes / 1024, 2) . ' KB';
           elseif ($bytes < 1073741824)
                return round($bytes / 1048576, 2) . ' MB';
           elseif ($bytes < 1099511627776)
                return round($bytes / 1073741824, 2) . ' GB';
           else
                return round($bytes / 1099511627776, 2) . ' TB';
      }

      public function addPrefix($prefix)
      {
           $prefix = trim($prefix);
           $file_name = $prefix . $this->getName();
           $file_full_path = $this->getPath() . "/" . $file_name;
           $this->setName($file_name);
           $this->setFullPath($file_full_path);
      }

      public function delete()
      {
           $deleted = false;
           $path = $this->getFullPath();

           if (!file_exists($path))
           {
                if (!unlink($path))
                {
                     $deleted = false;
                     $this->setError("No se pudo borrar el archivo.");
                }
           }
           else
           {
                $this->setError("No se encontro el archivo.");
           }

           return $deleted;
      }

 }

?>
