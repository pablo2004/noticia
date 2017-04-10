<nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">

  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".side-bar">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#"><i class="fa fa-newspaper-o"></i> Noticias 1.0</a>
  </div>
    
        <div class="collapse navbar-collapse" id="navbar-main">
        
            <form class="navbar-form navbar-right">
                 <?php
                      if(AppController::isRol('Administrador'))
                      {
                           $Particion = AppController::_newInstance('Particion');
                           $Particion = $Particion->find('list');
                           $Actual = user('particion_id');
                           echo $this->Form->input("CambioParticion", array('class' => 'form-control', 'label' => false, 'value' => $Actual, 'options' => $Particion));
                      }
                 ?>
            </form>

        </div>

</nav>