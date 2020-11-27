<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location:login.php");
} else {

    require 'header.php';

    if ($_SESSION['administrador'] == 1) {
?>
        <!-- page content -->
        <div class="right_col" role="main">
        <input type="hidden" id="administrador" name="administrador" value=<?php echo $_SESSION['administrador'] ?>>
            <div class="">
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>REPORTE USO DE REPUESTOS</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div id="filtro" class="x_content">
                                <div class="form-group">
                                    <div class="col-md-3 col-sm-3 col-xs-6">
                                        <label>PATENTE</label>
                                        <select id="patente" name="patente" class="selectpicker form-control"></select>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6">
                                        <label>DESDE</label>
                                        <input type='date' id="fhDesde" name="fhDesde" class="form-control" />
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6">
                                        <label>HASTA</label>
                                        <input type='date' id="fhHasta" name="fhHasta" class="form-control" />
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6">
                                        <br>
                                        <button type="button" class="btn btn-info" onclick="buscar();"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div id="listadovehiculosgestion" class="x_content">
                                <table id="tblReporte" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>PATENTE</th>
                                            <th>COD. REPUESTO</th>
                                            <th>REPUESTO</th>
                                            <th>MARCA</th>
                                            <th>MODELO</th>
                                            <th>CANTIDAD</th>
                                            <th>FH USO</th>
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
        <div id="ModalDetalle" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="Detalleform" class="form-horizontal calender" role="form" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">DETALLE DE SERVICIO TALLER MECÁNICO</h4>
                        </div>
                        <div class="modal-body">
                            <div id="testmodal" style="padding: 5px 10px;">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <label>SERVICIO REALIZADO POR</label>
                                        <input type="text" class="form-control" name="mecanico" id="mecanico">
                                    </div>
                                    <div class="col-md-6">
                                        <label>CHOFER ASIGNADO</label>
                                        <input type="text" class="form-control" name="chofer" id="chofer">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label>OBSERVACIONES</label>
                                        <textarea style="height:70px; text-transform: uppercase; resize:none; width:100%;" id="obsServicio" name="obsServicio"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label>REPUESTOS UTILIZADOS</label>
                                        <table id="tblDetalle" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" style="margin-top: -15px !important;">
                                            <thead>
                                                <tr>
                                                    <th>Repuesto</th>
                                                    <th>Cantidad</th>
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

        <div id="btnDetalle" data-toggle="modal" data-target="#ModalDetalle"></div>


    <?php
    } else {
        require 'nopermiso.php';
    }
    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/reporteTallerMecanico.js"></script>
<?php
}
ob_end_flush();
?>