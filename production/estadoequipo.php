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
              <h3>Estado de los Equipos</h3>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-desktop"></i>&nbsp;Equipos</span>
              <div class="count"><a href="#" id="equipos"></a></div>              
            </div>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
                <span class="count_top"><i class="fa fa-desktop"></i>&nbsp;Equipos Asignados</span>
              <div class="count"><a href="#" id="equipos_asignados"></a></div>              
            </div>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-desktop"></i>&nbsp;Equipos Libres</span>
              <div class="count green"><a href="#" id="equipos_libres"></a></div>
            </div>  
            
              <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-desktop"></i>&nbsp;Equipos Dañados</span>
              <div class="count green"><a href="#" id="equipos_averiados"></a></div>
            </div>  
              
              <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-desktop"></i>&nbsp;Equipos Robados</span>
              <div class="count green"><a href="#" id="equipos_robados"></a></div>
            </div>  
              
          </div>
            
          <div class="clearfix"></div>
          <div class="ln_solid"></div> 
          
           <div class="row tile_count">
              <h3>Tipos de Equipo</h3>
            
            <div class="col-md-3 col-sm-3 col-xs-3 tile_stats_count">
              <span class="count_top"><i class="fa fa-desktop"></i>&nbsp;Escritorio</span>
              <div class="count"><a href="#" id="escritorio"></a></div>              
            </div>
            
            <div class="col-md-3 col-sm-3 col-xs-3 tile_stats_count">
                <span class="count_top"><i class="fa fa-tablet"></i>&nbsp;Tableta</span>
              <div class="count"><a href="#" id="tableta"></a></div>              
            </div>
            
            <div class="col-md-3 col-sm-3 col-xs-3 tile_stats_count">
              <span class="count_top"><i class="fa fa-laptop"></i>&nbsp;Laptop</span>
              <div class="count green"><a href="#" id="laptop"></a></div>
            </div>  
            
              <div class="col-md-3 col-sm-3 col-xs-3 tile_stats_count">
                  <span class="count_top"><i class="fa fa-desktop"></i>&nbsp;Todo en Uno</span>
              <div class="count green"><a href="#" id="todo_en_uno"></a></div>
            </div>  
               
              
          </div>
        </div>
        <!-- /Fin Contenido -->

<?php 
require 'footer.php';
?>
        <script type="text/javascript" src="scripts/estadoequipo.js"></script>

<?php
}
ob_end_flush();
?>
