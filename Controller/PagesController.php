<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController {

     public $name = 'Pages';
     public $uses = array();

     public function display() 
     {
          if(isUser())
          {
               $this->redirect(rol('url'));
          }
     }

}
