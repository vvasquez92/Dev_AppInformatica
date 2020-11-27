<?php
ob_start();
session_start();

if(!isset($_SESSION["nombre"])){
  header("Location:login.php");
}else{

require 'header.php';


 ?>
        <!-- Contenido -->
        <div class="right_col" role="main">
        
          <!-- Datos moviles -->
          <div class="row tile_count">
            <h3>Telefonos moviles</h3>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-mobile"></i> Moviles</span>
              <div class="count"><a href="#" id="moviles"></a></div>              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-mobile"></i> Moviles asignados </span>
              <div class="count"><a href="#" id="moasig"></a></div>
              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-mobile"></i> Moviles libres </span>
              <div class="count green"><a href="#" id="molibres"></a></div>
              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-tag"></i>Tarjetas SIM </span>
              <div class="count"><a href="#" id="sim"></a></div>
              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-wrench"></i> SIM asignadas </span>
              <div class="count"><a href="#" id="simasig"></a></div>
              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-tag"></i> SIM libres </span>
              <div class="count"><a href="#" id="simlibres"></a></div>              
            </div>          
          </div>
          
          <div class="clearfix"></div>
          <div class="ln_solid"></div> 
          
          <div class="row tile_count">
            <h3>Equipos</h3>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-tag"></i> Equipos</span>
              <div class="count"><a href="#" id="equipos"></a></div>
              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-exclamation-triangle"></i> Equipos asignados </span>
              <div class="count"><a href="#" id="equiasig"></a></div>
              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-fire-extinguisher"></i> Equipos libres </span>
              <div class="count green"><a href="#" id="equilibres"></a></div>
              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-bullseye"></i>Notebooks  </span>
              <div class="count"><a href="#" id="note"></a></div>
              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-wrench"></i> Desktops </span>
              <div class="count"><a href="#" id="desk"></a></div>
              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-bolt"></i> Tabletas </span>
              <div class="count"><a href="#" id="table"></a></div>              
            </div>          
          </div>
          
          <div class="clearfix"></div>
          <div class="ln_solid"></div> 
          
          <div class="row tile_count">
            <h3>Vehículos</h3>
            <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-car"></i> Disponibles</span>
              <div class="count"><a href="#" id="vehiculos"></a></div>
              
            </div>
            <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user-tie"></i> Asignados </span>
              <div class="count"><a href="#" id="vehiasig"></a></div>
              
            </div>
            <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-car-side"></i> Libres </span>
              <div class="count green"><a href="#" id="vehilibres"></a></div>
            </div>                  
          </div>
        </div>
        <!-- /Fin Contenido -->

<?php 
require 'footer.php';
?>
<script type="text/javascript" src="scripts/estado.js"></script>
<link href="../public/build/css/all.css" rel="stylesheet">

<?php
}
ob_end_flush();
?>
