<?php 
ob_start();
session_start();

if(!isset($_SESSION["nombre"])){
  header("Location:login.php");
}else{

require 'header.php';

if( $_SESSION['administrador']==1)
{

 ?>
        
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Bitacora Ascensores</h2>
                  
                    <div class="clearfix"></div>
                  </div>
                  <div id="listadobitacora" class="x_content">
                    <table id="tblbitacora" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Identificador</th>
                          <th>Edificio</th>
                          <th>Recorridos</th>
                          <th>Tiempo Recorridos</th>
                          <th>Fecha y Hora</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
<?php 
}else{
  require 'nopermiso.php';
}
require 'footer.php';
?>
<script type="text/javascript" src="scripts/usuario.js"></script>
<?php 
}
ob_end_flush();
?>