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
               
          <div class="row tile_count">
              <h3>Estado de los Tel&eacute;fonos M&oacute;viles</h3>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
                <span class="count_top"><i class="fa fa-mobile"></i>&nbsp;M&oacute;viles</span>
              <div class="count"><a href="#" id="moviles"></a></div>              
            </div>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
                <span class="count_top"><i class="fa fa-mobile"></i>&nbsp;M&oacute;viles Asignados</span>
              <div class="count"><a href="#" id="moviles_asignados"></a></div>              
            </div>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
                <span class="count_top"><i class="fa fa-desfa fa-mobile"></i>&nbsp;M&oacute;viles Libres</span>
              <div class="count green"><a href="#" id="moviles_libres"></a></div>
            </div>  
            
              <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
                  <span class="count_top"><i class="fa fa-mobile"></i>&nbsp;M&oacute;viles Descompuestos</span>
              <div class="count green"><a href="#" id="moviles_descompuestos"></a></div>
            </div>  
              
              <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
                  <span class="count_top"><i class="fa fa-mobile"></i>&nbsp;M&oacute;viles Robados</span>
              <div class="count green"><a href="#" id="moviles_robados"></a></div>
            </div>  
              
          </div>
            
          <div class="clearfix"></div>
          <div class="ln_solid"></div> 
          
           <div class="row tile_count">
              <h3>Estado de las Tarjetas SIM</h3>
            
            <div class="col-md-3 col-sm-3 col-xs-3 tile_stats_count">
              <span class="count_top"><i class="fa fa-desktop"></i>&nbsp;Tarjetas SIM</span>
              <div class="count"><a href="#" id="sim"></a></div>              
            </div>
            
            <div class="col-md-3 col-sm-3 col-xs-3 tile_stats_count">
                <span class="count_top"><i class="fa fa-tablet"></i>&nbsp;Tarjetas SIM Asignadas</span>
              <div class="count"><a href="#" id="sim_asignadas"></a></div>              
            </div>
            
            <div class="col-md-3 col-sm-3 col-xs-3 tile_stats_count">
              <span class="count_top"><i class="fa fa-laptop"></i>&nbsp;Tarjetas SIM Libres</span>
              <div class="count green"><a href="#" id="sim_libres"></a></div>
            </div>  
            
              <div class="col-md-3 col-sm-3 col-xs-3 tile_stats_count">
                  <span class="count_top"><i class="fa fa-desktop"></i>&nbsp;Tarjetas SIM Robadas</span>
              <div class="count green"><a href="#" id="sim_robadas"></a></div>
            </div>  
               
              
          </div>
        </div>
        <!-- /Fin Contenido -->

<?php 
require 'footer.php';
?>
        <script type="text/javascript" src="scripts/estadomoviles.js"></script>

<?php
}
ob_end_flush();
?>
