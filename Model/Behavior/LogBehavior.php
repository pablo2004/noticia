<?php
  

class LogBehavior extends ModelBehavior
{

     public function afterSave(Model $Model, $created, $options = array())
     {
          App::uses('Log', 'Model');
          
          if(isUser())
          {
               $Log = new Log();
               $data['usuario_id'] = user('id');
               $data['modelo'] = $Model->name;
               $data['accion'] = ($created) ? 'guardar' : 'cambiar';
               $data['registro'] = $Model->id;
               $data['fecha_alta'] = $this->_getDate();
               $Log->create();
               $Log->save($data);
          }
     }

     public function afterDelete(Model $Model)
     {
          App::uses('Log', 'Model');

          if(isUser())
          {
               $Log = new Log();
               $data['usuario_id'] = user('id');
               $data['modelo'] = $Model->name;
               $data['accion'] = 'borrar';
               $data['registro'] = $Model->id;
               $data['fecha_alta'] = $this->_getDate();
               $Log->create();
               $Log->save($data);
          }
     }
      

     private function _getDate()
     {
          return date("Y-m-d h:i:s");
     }
	  
}

?>
