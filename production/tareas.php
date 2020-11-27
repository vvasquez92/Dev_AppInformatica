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
                                <h2>TAREAS</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-tooltip="tooltip" title="Operaciones" role="button" aria-expanded="false"><i class="fa fa-cog"></i></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a id="op_agregar" onclick="mostarform(true)">AGREGAR</a>
                                            </li>
                                            <li><a id="op_listar" onclick="mostarform(false)">LISTAR</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div id="listadotareas" class="x_content">

                                <table id="tbltareas" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>EMPLEADO</th>
                                            <th>TAREA</th>
                                            <th>INICIO</th>
                                            <th>FIN</th>
                                            <th>ESTADO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div id="formulariotareas" class="x_content">
                                <br />

                                <div class="col-md-12 center-margin">
                                    <form class="form-horizontal form-label-left" id="formulario" name="formulario">
                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                            <label>EMPLEADO</label>
                                            <input type="hidden" id="idtarea" name="idtarea" class="form-control">
                                            <select class="form-control selectpicker" data-live-search="false" id="idempleado" name="idempleado" required="required"></select>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                            <label>TAREA</label>
                                            <input type="text" class="form-control" name="tit_tarea" id="tit_tarea" required="">
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                            <label>FECHA INICIO</label>
                                            <input type="text" class="form-control" name="tit_fhini" id="tit_fhini" required="">
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                            <label>HORA INICIO</label>
                                            <input type="text" class="form-control" name="tit_horaini" id="tit_horaini">
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                            <label>FECHA FIN</label>
                                            <input type="text" class="form-control" name="tit_fhfin" id="tit_fhfin" required="">
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                            <label>HORA FIN</label>
                                            <input type="text" class="form-control" name="tit_horafin" id="tit_horafin">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                            <label>COMENTARIO</label>
                                            <input type="text" class="form-control" name="tit_comentario" id="tit_comentario" required="">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="ln_solid"></div>

                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <button class="btn btn-primary" type="button" id="btnCancelar" onclick="cancelarform()">Cancelar</button>
                                                <button class="btn btn-primary" type="reset" id="btnLimpiar" onclick="limpiar()">Limpiar</button>
                                                <button class="btn btn-success" type="submit" id="btnGuardar">Agregar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->

    <?php
    } else {
        require 'nopermiso.php';
    }
    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/tareas.js"></script>
<?php
}
ob_end_flush();
?>