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
            <div class="">

                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>TICKETS</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-tooltip="tooltip" title="Histórico" role="button" aria-expanded="false"><i class="fa fa-history"></i></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a id="op_historico" onclick="historico(true)">HISTÓRICO</a>
                                            </li>
                                            <li><a id="op_listar_" onclick="historico(false)">ACTUAL</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div id="listadotickets" class="x_content">

                                <table id="tbltickets" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>FECHA</th>
                                            <th>TIPO TICKET</th>
                                            <th>TIPO SOLICITUD</th>
                                            <th>EMPLEADO</th>
                                            <th>ESTADO</th>
                                            <th>RESPUESTA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div id="listadoticketshistorico" class="x_content">

                                <table id="tblticketshistorico" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>FECHA</th>
                                            <th>TIPO TICKET</th>
                                            <th>TIPO SOLICITUD</th>
                                            <th>EMPLEADO</th>
                                            <th>ESTADO</th>
                                            <th>RESPUESTA</th>
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
        <div class="modal fade" id="modalGestion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title" id="">Realizar Gesti&oacute;n</h4>
                    </div>
                    <form id="formGestion" name="formGestion">
                        <input type="hidden" name="idticket" id="idticket" value="">
                        <div class="modal-body">
                        <div class="form-group">
                                <label for="tipo_ticket">TIPO TICKET</label>
                                <select class="form-control selectpicker" data-live-search="false" id="idtipoticket" name="idtipoticket" required="required" disabled>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tipo_solicitud">TIPO SOLICITUD</label>
                                <select class="form-control selectpicker" data-live-search="false" id="idtipo" name="idtipo" required="required" disabled>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="empleado">EMPLEADO</label>
                                <select class="form-control selectpicker" data-live-search="true" id="idempleado" name="idempleado" required="required" disabled></select>
                            </div>
                            <div class="form-group">
                                <label for="observacion">OBSERVACION</label>
                                <input type="text" class="form-control" name="observacion" id="observacion" disabled>
                            </div>
                            <div class="form-group">
                                <label for="respuesta">RESPUESTA</label>
                                <input type="text" class="form-control" name="respuesta" id="respuesta">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
    } else {
        require 'nopermiso.php';
    }
    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/ticket_admin.js"></script>
<?php
}
ob_end_flush();
