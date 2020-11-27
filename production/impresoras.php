<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

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
              <div class="count"><a href="#" id="moviles">0</a></div>
              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-mobile"></i> Moviles asignados </span>
              <div class="count"><a href="#" id="moasig">0</a></div>
              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-mobile"></i> Moviles libres </span>
              <div class="count green"><a href="#" id="molibres">0</a></div>
              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-tag"></i>Tarjetas SIM </span>
              <div class="count"><a href="#" id="sim">0</a></div>
              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-wrench"></i> SIM asignadas </span>
              <div class="count"><a href="#" id="simasig">0</a></div>
              
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-tag"></i> SIM libres </span>
              <div class="count"><a href="#" id="simlibres">0</a></div>              
            </div>          
          </div>
          
          <div class="clearfix"></div>
          <div class="ln_solid"></div> 

        </div>
        <!-- /Fin Contenido -->

<?php 
require 'footer.php';
?>
        <script type="text/javascript" src="scripts/impresoras.js"></script>

<?php
}
ob_end_flush();
?>
