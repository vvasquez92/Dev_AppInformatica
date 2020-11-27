<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location:login.php");
} else {

    require 'header.php';

    if ($_SESSION['administrador'] == 1 || $_SESSION['vehiculos'] == 1) {

?>

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <input type="hidden" id="administrador" name="administrador" value=<?php echo $_SESSION['administrador'] ?>>
                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>REPUESTOS</h2>
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
                            <div id="listadorepuestos" class="x_content">

                                <table id="tblrepuestos" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>NOMBRE</th>
                                            <th>CATEGORÍA</th>
                                            <th>MARCA</th>
                                            <th>MODELO</th>
                                            <th>CANTIDAD</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div id="formulariorepuestos" class="x_content">
                                <br />

                                <div class="col-md-12 center-margin">
                                    <form class="form-horizontal form-label-left" id="formformularioRepuestos" name="formformularioRepuestos">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                <label>CÓDIGO PRODUCTO</label><span class="required">*</span>
                                                <input type="hidden" name="id_repuesto" id="id_repuesto">
                                                <input type="hidden" name="id_factura_rep" id="id_factura_rep">
                                                <input type="hidden" name="sumaStock" id="sumaStock">
                                                <input type="hidden" name="editCod_producto" id="editCod_producto">
                                                <input type="hidden" name="nombreFact" id="nombreFact">
                                                <input type="text" class="form-control" name="cod_producto" id="cod_producto" required="required" maxlength="45" onblur="validaCodigoProducto()">
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                <label>NÚMERO DE SERIE</label><span class="required">*</span>
                                                <input type="text" class="form-control" name="nro_serie" id="nro_serie" maxlength="45"><br>
                                                <button class="btn btn-primary" type="button" id="btnVerNroSerie" onclick="verNroSerie()">VER NÚMEROS AGREGADOS</button>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                <label>NOMBRE</label><span class="required">*</span>
                                                <input type="text" class="form-control" name="nombre_rep" id="nombre_rep" required="required" style="text-transform: uppercase;" maxlength="100">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-sm-3 col-xs-10 form-group">
                                                <label>MARCA</label><span class="required">*</span>
                                                <select class="form-control selectpicker" data-live-search="true" id="marca_rep" name="marca_rep" required="required">
                                                </select>
                                            </div>
                                            <div class="col-md-1 col-sm-1 col-xs-2 form-group">
                                                <button class="btn btn-primary" type="button" id="btnAgregarMarca" onclick="agregarMarca()" title="Agregar Marca" style="margin-top:25px;">+</button>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                <label>MODELO</label><span class="required">*</span>
                                                <input type="text" class="form-control" name="modelo_rep" id="modelo_rep" required="required" style="text-transform: uppercase;" maxlength="100">
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                <label>CATEGORÍA</label><span class="required">*</span>
                                                <select class="form-control selectpicker" data-live-search="true" id="categoria_rep" name="categoria_rep" required="required">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-sm-3 col-xs-10 form-group">
                                                <label>PROVEEDOR</label><span class="required">*</span>
                                                <select class="form-control selectpicker" data-live-search="true" id="proveedor_rep" name="proveedor_rep" required="required">
                                                </select>
                                            </div>
                                            <div class="col-md-1 col-sm-1 col-xs-2 form-group">
                                                <button class="btn btn-primary" type="button" id="btnAgregarProveedor" onclick="agregarProveedor()" title="Agregar Proveedor" style="margin-top:25px;">+</button>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                <label>FACTURA</label><span class="required">*</span>
                                                <input type="text" class="form-control" name="nro_factura" id="nro_factura" required="required" maxlength="11">
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                <label>ARCHIVO (MÁX. 3MB)</label><span class="required">*</span>
                                                <div class="row">
                                                    <div class="col-md-10 col-sm-10 col-xs-6 form-group">
                                                        <input type="file" id="file_factura" name="file_factura" class="custom-file-input">
                                                    </div>
                                                    <div class="col-md-2 col-sm-2 col-xs-6 form-group">
                                                        <button class="btn btn-primary" type="button" id="btnVerFactura" onclick="verFactura()" title="Ver factura"><i class="fa fa-file"></i></button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                <label>PRECIO NETO</label><span class="required">*</span>
                                                <input type="number" class="form-control" name="precio_rep" id="precio_rep" min="0" required="required" max="99999999999" onKeyPress="if(this.value.length==11) return false;">
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                <label>CANTIDAD</label><span class="required">*</span>
                                                <input type="number" class="form-control" name="cantidad_rep" id="cantidad_rep" min="0" required="required" max="99999999999" onKeyPress="if(this.value.length==11) return false;">
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                <label>UBICACIÓN</label><span class="required">*</span>
                                                <input type="text" class="form-control" name="ubicacion_rep" id="ubicacion_rep" required="required" style="text-transform: uppercase;" maxlength="100"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                                <label>COMENTARIOS</label>
                                                <textarea type="text" class="form-control" name="observaciones_rep" id="observaciones_rep" required="required" style="text-transform: uppercase; resize: none;" maxlength="200"></textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="ln_solid"></div>

                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <button class="btn btn-primary" type="button" id="btnCancelar" onclick="cancelarform()">Cancelar</button>
                                                <button class="btn btn-primary" type="reset" id="btnLimpiar" onclick="limpiar()">Limpiar</button>
                                                <button class="btn btn-success" type="submit" id="btnGuardar">Guardar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="ModalNewMarca" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="Marcaform" class="form-horizontal calender" role="form" enctype="multipart/form-data">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title" id="myModalLabel">AGREGAR NUEVA MARCA</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="testmodal" style="padding: 5px 10px;">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label class="control-label">MARCA</label>
                                                    <textarea class="form-control" style="height:35px; width:100%; text-transform: uppercase; resize:none;" id="NewMarca" name="NewMarca" placeholder="INGRESE NOMBRE DE LA MARCA" maxlength="100" required="required"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" id="enviar" class="btn btn-primary">Guardar</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="ModalNewProveedor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="Proveedorform" class="form-horizontal calender" role="form" enctype="multipart/form-data">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title" id="myModalLabel">AGREGAR NUEVO PROVEEDOR</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="testmodal" style="padding: 5px 10px;">
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <label class="control-label">NOMBRE PROVEEDOR</label>
                                                    <input type="text" class="form-control" name="newNombreProv" id="newNombreProv" style="text-transform: uppercase;" maxlength="45" required="required">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="control-label">RUT</label>
                                                    <input type="text" class="form-control" name="newRutProv" id="newRutProv" style="text-transform: uppercase;" data-inputmask="'mask' : '99.999.999-*'" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label class="control-label">DIRECCIÓN</label>
                                                    <input type="text" class="form-control" name="newDireccionProv" id="newDireccionProv" style="text-transform: uppercase;" maxlength="100" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <label class="control-label">REGIÓN</label>
                                                    <select class="form-control selectpicker" data-live-search="true" id="idregiones" name="idregiones" required="required">
                                                        <option value="" selected disabled>SELECCIONE</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="control-label">COMUNA</label>
                                                    <select class="form-control selectpicker" data-live-search="true" id="idcomunas" name="idcomunas" required="required">
                                                        <option value="" selected disabled>SELECCIONE</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <label class="control-label">NOMBRE CONTACTO</label>
                                                    <input type="text" class="form-control" name="newTelefonoProv" id="newContactoProv" style="text-transform: uppercase;" maxlength="50" required="required">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="control-label">TELÉFONO</label>
                                                    <input type="text" class="form-control" name="newTelefonoProv" id="newTelefonoProv" data-inputmask="'mask' : '999999999'" style="text-transform: uppercase;" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label class="control-label">CORREO ELECTRÓNICO</label>
                                                    <input type="text" class="form-control" name="newCorreoProv" id="newCorreoProv" style="text-transform: lowercase;" maxlength="100" required="required">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" id="enviar" class="btn btn-primary">Guardar</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="ModalDetalle" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" style="min-width:800px !important">
                            <form id="Detalleform" class="form-horizontal calender" role="form" enctype="multipart/form-data">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title" id="myModalLabel">DETALLE DE MOVIMIENTOS</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="testmodal" style="padding: 5px 10px;">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <table id="tblmovimientos" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>TIPO</th>
                                                                <th>FECHA</th>
                                                                <th>CANTIDAD</th>
                                                                <th>NRO. FACTURA</th>
                                                                <th>NRO. SERVICIO</th>
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
                            </form>
                        </div>
                    </div>

                    <div id="ModalNroSerie" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="Marcaform" class="form-horizontal calender" role="form" enctype="multipart/form-data">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title" id="myModalLabel">AGREGAR NÚMEROS DE SERIE</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="testmodal" style="padding: 5px 10px;">
                                            <div class="form-group">
                                                <div class="col-sm-11 col-sm-11 col-xs-11 form-group">
                                                    <label class="control-label">NRO. DE SERIE</label>
                                                    <input type="text" class="form-control" id="NewNroSerie" name="NewNroSerie" placeholder="INGRESE NRO. DE SERIE" maxlength="45">
                                                </div>
                                                <div class="col-md-1 col-sm-1 col-xs-1 form-group">
                                                    <button class="btn btn-primary" type="button" id="btnAgregarProveedor" onclick="agregarNroSerie()" title="Agregar Nro de Serie" style="margin-top:25px;">+</button>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label class="control-label" id="lblCan"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <table id="tblNroSerie" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Cerrar</button>
                                        <button type="button" id="enviar" class="btn btn-primary" onclick="guardaNroSerie()">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="ModalFacturas" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="Facturaform" class="form-horizontal calender">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title" id="myModalLabel">FACTURAS ASOCIADAS</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="testmodal" style="padding: 5px 10px;">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <table id="tblFacturas" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>FACTURA</th>
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
                            </form>
                        </div>
                    </div>

                    <div id="btnSumMarca" data-toggle="modal" data-target="#ModalNewMarca"></div>
                    <div id="btnSumProveedor" data-toggle="modal" data-target="#ModalNewProveedor"></div>
                    <div id="btnDetalleMov" data-toggle="modal" data-target="#ModalDetalle"></div>
                    <div id="btnNroSerie" data-toggle="modal" data-target="#ModalNroSerie"></div>
                    <div id="btnFacturas" data-toggle="modal" data-target="#ModalFacturas"></div>
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
    <script>
        $('.form_datetime').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    </script>
    <script type="text/javascript" src="scripts/bodega_repuestos.js"></script>
<?php
}
ob_end_flush();
?>