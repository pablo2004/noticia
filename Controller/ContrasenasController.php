<?php

App::uses('AppController', 'Controller');

class ContrasenasController extends AppController 
{

     public function __construct($request = null, $response = null) 
     {
          $this->setModel('Contrasena');
          $this->setName("Contrasenas");
          parent::__construct($request, $response);
          $this->startController();
     }

     public function beforeFilter()
     {
          parent::beforeFilter();
          $this->Auth->allow('*');
     }

     public function alta($return = false)
     {

          $particion_id = intval(Hash::get($_GET, 'particion_id'));
          $RECAPTCHA_PUBLIC_KEY  = RECAPTCHA_PUBLIC_KEY;
	     $RECAPTCHA_PRIVATE_KEY = RECAPTCHA_PRIVATE_KEY;
	     $recaptcha_html = "";
          App::import("Vendor", "recaptchalib");

          if($particion_id == 0)
          {
               $this->redirect('/usuarios/login/?particion_id=1');
          }

          if($this->request->is('post'))
          {	 
		       $request = $this->request->data;
		       $code = uniqid();
		       $recaptcha = true;
		       $Usuario = AppController::_newInstance('Usuario');
		
		       if(!empty($RECAPTCHA_PRIVATE_KEY))
		       {
		            $response = recaptcha_check_answer($RECAPTCHA_PRIVATE_KEY, $this->RequestHandler->getClientIp(), $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

		            if(!$response->is_valid)
		            {
		                 $recaptcha = false;
                           $this->getModel()->addError("captcha", "Codigo de Seguridad Incorrecto.");
		            }
		       }
		 
		       $Usuario = $Usuario->findByParticionIdAndEmail($particion_id, $request['Contrasena']['email']); 
                
		       if(sizeof($Usuario) > 0)
		       {
		       	  $request['Contrasena']['particion_id'] = $particion_id;
		            $request['Contrasena']['codigo'] = $code;
		            $request['Contrasena']['activo'] = 1;
		       }
		       else
		       {
		            $recaptcha = false;
                      $this->getModel()->addError("email", "Este correo no se encontro en el sistema.");
		       }
		
		       if($recaptcha)
		       {
                      $this->getModel()->setDataToStore($request);
                      $this->getModel()->store();

		            if($this->getModel()->isStored())
		            {
		                 $url = SITE_URL.Router::url($this->getControllerPath()."confirmar/".$code."?particion_id=".$particion_id);
		                 $from_name = "Sistema";
		                 $from_mail = 'bibliotecadigital@tamaulipas.gob.mx';
		                 $subject = "Recuperar Password: ".$from_name;
		                 $message = 'Da click en cualquiera de los siguientes 2 enlace para confirmar tu cambio de password:<br><br><hr>'.$url.'<br><br><a href="'.$url.'">'.$url.'</a><br><br>';
			 	 
		                 AppController::sendMail($request['Contrasena']['email'], $subject, $message, $from_name, $from_mail);
		            }

		       }

                die($this->getModel()->storeJSONResult());
          }
          else
          {
	          if(!empty($RECAPTCHA_PUBLIC_KEY))
	          {
	               $recaptcha_html = recaptcha_get_html($RECAPTCHA_PUBLIC_KEY, null);
	          }

               $this->set('validate', $this->getModel()->getJSONValidations());
	          $this->set("recaptcha_html", $recaptcha_html);
	          $this->set("particion_id", $particion_id);
          }
     }
      
      
     public function confirmar($codigo = '')
     {

          $particion_id = intval(Hash::get($_GET, 'particion_id'));
          $RECAPTCHA_PUBLIC_KEY  = RECAPTCHA_PUBLIC_KEY;
	     $RECAPTCHA_PRIVATE_KEY = RECAPTCHA_PRIVATE_KEY;
	     $recaptcha_html = "";
	     App::import("Vendor", "recaptchalib");
	     $codigo = trim($codigo);

          if($particion_id == 0)
          {
               $this->redirect('/usuarios/login/?particion_id=1');
          }
	  
          if($this->request->is('post'))
          {
	          $recaptcha = true;
               $request = $this->request->data;

               if(!empty($RECAPTCHA_PRIVATE_KEY))
               {
                    $response = recaptcha_check_answer($RECAPTCHA_PRIVATE_KEY, $this->RequestHandler->getClientIp(), $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

	               if(!$response->is_valid)
                    {
	                     $recaptcha = false;
	                     $this->getModel()->addError("captcha", "Codigo de seguridad incorrecto.");
                    }
               }
		    
               if($recaptcha)
               {
                    $Contrasena = $this->getModel()->findByCodigoAndActivo($request['Contrasena']['codigo'], 1);
		          $this->getModel()->id = $Contrasena['Contrasena']['id'];
			 
		          if($this->getModel()->exists())
		          {
		               $this->getModel()->saveField('activo', 0);
                         $Usuario = AppController::_newInstance('Usuario');
                         $Usuario_Busca = $Usuario->findByParticionIdAndEmail($particion_id, $Contrasena['Contrasena']['email']);
                         $Usuario->off("Log");
                         $Usuario->id = $Usuario_Busca['Usuario']['id'];
                         $password = uniqid();
                         $Usuario->saveField('password', $password);
			      
                         $url = SITE_URL.Router::url('/usuarios/login/?particion_id='.$particion_id);
                         $usuario = $Usuario->read();
                         $email = $Usuario_Busca['Usuario']['email'];
			 
                         $from_name = "Sistema";
                         $from_mail = 'bibliotecadigital@tamaulipas.gob.mx';
                         $subject = "Cambio de Password: ".$from_name;
                         $message = "Tus nuevos datos de acceso son:<br><hr>Email: $email<br>Password: $password<br>Acceso: $url";
			 	 
                         AppController::sendMail($email, $subject, $message, $from_name, $from_mail);
		          }
		          else
		          {
		               $this->getModel()->addError("codigo", "Codigo de confirmacion no valido o ya usado.");
		          }
               
	           }
		    
               die($this->getModel()->storeJSONResult());
          }
          else
          {
               if(!empty($RECAPTCHA_PUBLIC_KEY))
	          {
	                $recaptcha_html = recaptcha_get_html($RECAPTCHA_PUBLIC_KEY, null);
	          }

               $this->set('validate', $this->getModel()->getJSONValidations());
	          $this->set("codigo", $codigo);
	          $this->set("particion_id", $particion_id);
	          $this->set("recaptcha_html", $recaptcha_html);
          }

     }

}

?>