<?php 
ob_start();

if(!isset($_SESSION["nombre"])){
  header("Location:login.php");
}else{

 ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Acceso denegado</h3>
                <br />
                <h3><small><strong>No tiene permisos ingresar a este modulo</strong></small></h3>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

<?php 
}
ob_end_flush();
?>
