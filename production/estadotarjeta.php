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
              <h3>Estado de las Tarjetas</h3>
            
            <div class="col-md-4 col-sm-4 col-xs-4 tile_stats_count">
              <span class="count_top"><i class="fa fa-address-card-o"></i>&nbsp;Tarjetas</span>
              <div class="count"><a href="#" id="tarjetas"></a></div>              
            </div>
            
            <div class="col-md-4 col-sm-4 col-xs-4 tile_stats_count">
                <span class="count_top"><i class="fa fa-address-card-o"></i>&nbsp;Tarjetas Asignadas</span>
              <div class="count"><a href="#" id="tarjetas_asignadas"></a></div>              
            </div>
            
            <div class="col-md-4 col-sm-4 col-xs-4 tile_stats_count">
              <span class="count_top"><i class="fa fa-address-card-o"></i>&nbsp;Tarjetas Libres</span>
              <div class="count green"><a href="#" id="tarjetas_libres"></a></div>
            </div>
              
          </div>
            
          <div class="clearfix"></div>
          <div class="ln_solid"></div> 
          <div class="row tile_count">
              <h3>Niveles de Acceso</h3>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-address-card-o"></i>&nbsp;NIVEL 0</span>
              <div class="count"><a href="#" id="nivel_0"></a></div>              
            </div>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
                <span class="count_top"><i class="fa fa-address-card-o"></i>&nbsp;NIVEL 1</span>
              <div class="count"><a href="#" id="nivel_1"></a></div>              
            </div>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-address-card-o"></i>&nbsp;NIVEL 2</span>
              <div class="count green"><a href="#" id="nivel_2"></a></div>
            </div>
              
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-address-card-o"></i>&nbsp;NIVEL 3</span>
              <div class="count green"><a href="#" id="nivel_3"></a></div>
            </div>
              
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-address-card-o"></i>&nbsp;NIVEL 4</span>
              <div class="count green"><a href="#" id="nivel_4"></a></div>
            </div>
              
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-address-card-o"></i>&nbsp;NIVEL 5</span>
              <div class="count green"><a href="#" id="nivel_5"></a></div>
            </div>
              
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-address-card-o"></i>&nbsp;NIVEL 6</span>
              <div class="count green"><a href="#" id="nivel_6"></a></div>
            </div>
              
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-address-card-o"></i>&nbsp;NIVEL 7</span>
              <div class="count green"><a href="#" id="nivel_7"></a></div>
            </div>
              
          </div>
        </div>
        <!-- /Fin Contenido -->

<?php 
require 'footer.php';
?>
<script type="text/javascript" src="scripts/estadotarjeta.js"></script>

<?php
}
ob_end_flush();
?>
