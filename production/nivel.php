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
                                <h2>NIVELES DE ACCESO</h2>
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
                            <div id="listadoniveles" class="x_content">

                                <table id="tblniveles" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>NOMBRE</th>                           
                                            <th>CONDICION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div id="formularioniveles" class="x_content">
                                <br />

                                <div class="col-md-12 center-margin">
                                    <form class="form-horizontal form-label-left" id="formulario" name="formulario">
                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                            <label>NOMBRE</label>
                                            <input type="hidden" id="idnivel" name="idnivel" class="form-control">
                                            <input type="text" class="form-control" name="nombre" id="nombre">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                            <label>DESCRIPCION</label>
                                            <textarea type="text" id="descripcion" name="descripcion" class="form-control"></textarea>
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
    <script type="text/javascript" src="scripts/nivel.js"></script>
    <?php
}
ob_end_flush();
?>