<?php

App::uses("FilterBehavior", "Model/Behavior");
App::uses('Rol', 'Model');
App::uses('RolNivel', 'Model');

class FilterUserBehavior extends FilterBehavior
{

     public function setup(Model $model, $config = array())
     {
          $rol_id = user('rol_id');
          $user_id = user('id');

          if($rol_id > 0)
          {
               $roles = $this->getAccessRoles();
               $field = ($model->name == 'Usuario') ? $model->alias.'.id' : $model->alias.'.usuario_id';

               if(sizeof($roles) > 0)
               {
                    $roles = "(".implode(",", $roles).")";
                    $rol_id = rol('id');

                    $this->addFilterBefore($model->alias, "(Usuario.rol_id IN ".$roles." OR  $field = '".$user_id."')");
               }
               else
               {
                    $this->addFilterBefore($model->alias, "$field = '$user_id'");
               }
          }
     }

     public function getAccessRoles()
     {
          $rol_id = user('rol_id');
          $valids = array();
          $i = 0;
          $nivel = new RolNivel();
          $roles = new Rol();
          $roles = $roles->find("all"); 
          $niveles = $nivel->find("all"); 

          foreach($roles AS $rol)
          {
               if($nivel->isParent($rol_id, $rol['Rol']['id'], array(), $niveles))
               {
                    $valids[$i] = $rol['Rol']['id'];
                    $i++;
               }
          }

          return $valids;
     }
     
}

?>