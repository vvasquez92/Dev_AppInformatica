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
              <h3>Estado de los Veh&iacute;culos</h3>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-car"></i> Veh&iacute;culos<br>&nbsp; </span>
              <div class="count"><a href="#" id="vehiculos"></a></div>              
            </div>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
                <span class="count_top"><i class="fa fa-user-tie"></i> Veh&iacute;culos Asignados<br>&nbsp;</span>
              <div class="count"><a href="#" id="vehi_asig" onclick="listarVehAsig()"></a></div>              
            </div>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-car-side"></i> Veh&iacute;culos Libres<br>&nbsp;</span>
              <div class="count green"><a href="#" id="vehi_libres" onclick="listarVehLib()"></a></div>
            </div>  

            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
                <span class="count_top"><i class="fa fa-handshake"></i> Veh&iacute;culos<br>Devueltos </span>
              <div class="count green"><a href="#" id="vehi_dev" onclick="listarVehDet(1, 'VEHÍCULOS DEVUELTOS')"></a></div>
            </div>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
                <span class="count_top"><i class="fa fa-car-battery"></i> Veh&iacute;culos<br>en Mantenci&oacute;n </span>
              <div class="count green"><a href="#" id="vehi_mant" onclick="listarVehDet(5, 'VEHÍCULOS EN MANTECIÓN')"></a></div>
            </div>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
                <span class="count_top"><i class="fa fa-wrench"></i> Veh&iacute;culos<br>en Reparaci&oacute;n </span>
              <div class="count green"><a href="#" id="vehi_rep" onclick="listarVehDet(2, 'VEHÍCULOS EN REPARACIÓN')"></a></div>
            </div>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-car-crash"></i> Veh&iacute;culos<br>con Siniestro </span>
              <div class="count green"><a href="#" id="vehi_sin" onclick="listarVehDet(3, 'VEHÍCULOS CON SINIESTRO')"></a></div>
            </div>
            
            <div class="col-md-2 col-sm-2 col-xs-2 tile_stats_count">
              <span class="count_top"><i class="fa fa-mask"></i> Veh&iacute;culos<br>Robado </span>
              <div class="count green"><a href="#" id="vehi_rob" onclick="listarVehDet(4, 'VEHÍCULOS ROBADOS')"></a></div>
            </div>
            
          </div>
        </div>
        <!-- /Fin Contenido -->
        <div id="ModalVehDet" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="VehDetform" class="form-horizontal calender" role="form" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel"></h4>
                        </div>
                        <div class="modal-body">
                            <div id="testmodal" style="padding: 5px 10px;">                                
                                <div class="form-group" id="divTab">
                                    <div class="col-sm-12">
                                        <table id="tblVehDet" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>PATENTE</th>
                                                    <th>ÚLTIMA GESTIÓN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="ModalVehAsig" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="VehAsig" class="form-horizontal calender" role="form" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">VEHÍCULOS ASIGNADOS</h4>
                        </div>
                        <div class="modal-body">
                            <div id="testmodal" style="padding: 5px 10px;">                                
                                <div class="form-group" id="divTab">
                                    <div class="col-sm-12">
                                        <table id="tblVehAsig" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>PATENTE</th>
                                                    <th>COLABORADOR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="ModalVehLib" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="VehLib" class="form-horizontal calender" role="form" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">VEHÍCULOS LIBRES</h4>
                        </div>
                        <div class="modal-body">
                            <div id="testmodal" style="padding: 5px 10px;">                                
                                <div class="form-group" id="divTab">
                                    <div class="col-sm-12">
                                        <table id="tblVehLib" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>PATENTE</th>
                                                    <th>VEHICULO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="btnVehDet" data-toggle="modal" data-target="#ModalVehDet"></div>
        <div id="btnVehAsig" data-toggle="modal" data-target="#ModalVehAsig"></div>
        <div id="btnVehLib" data-toggle="modal" data-target="#ModalVehLib"></div>

<?php 
require 'footer.php';
?>
<script type="text/javascript" src="scripts/estadovehiculo.js"></script>
<link href="../public/build/css/all.css" rel="stylesheet">

<?php
}
ob_end_flush();
?>
