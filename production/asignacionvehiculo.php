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
                                <h2>ASIGNACIONES DE VEHÍCULOS</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-tooltip="tooltip" title="Operaciones" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-cog"></i></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a id="op_agregar" onclick="mostarform(1)">AGREGAR</a>
                                            </li>
                                            <li><a id="op_listar" onclick="mostarform(0)">LISTAR</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div id="listadoasigvehiculo" class="x_content">

                                <table id="tblasigvehiculo" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>COLABORADOR</th>
                                            <th>RUT</th>
                                            <th>PRESTAMO DE VEHÍCULO</th>
                                            <th>VEHÍCULO</th>
                                            <th>PATENTE</th>
                                            <th>FECHA</th>
                                            <th>ENTREGA</th>
                                            <th>DEVOLUCIÓN</th>
                                            <th>CONDICIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div id="formularioasigvehiculo" class="x_content">
                                <br />
                                <div class="col-md-12 center-margin">
                                    <form class="form-horizontal form-label-left" id="formformularioAsig" name="formformularioAsig">
                                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                            <label>VEHÍCULO <span class="required">*</span></label>
                                            <input type="hidden" id="idasigvehi" name="idasigvehi" class="form-control" required="Campo requerido">
                                            <input type="hidden" id="idasigvehic" name="idasigvehic" class="form-control" required="Campo requerido">
                                            <input type="hidden" id="idprestamo" name="idprestamo" class="form-control" required="Campo requerido"><br>
                                            <div id="selectVehiculo"><select class="form-control selectpicker" data-live-search="true" id="idvehiculo" name="idvehiculo"></select></div>
                                            <input type="text" id="lblDatosVehiculo" name="lblDatosVehiculo" class="form-control">
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                            <label>EMPLEADO <span class="required">*</span></label>
                                            <select class="form-control selectpicker" data-live-search="true" id="idempleado" name="idempleado" required="Campo requerido"></select>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                            <label>KILOMETRAJE <span class="required">*</span></label>
                                            <input type="number" id="kilometraje" name="kilometraje" class="form-control" required="Campo requerido" min="0" max="999999">
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                            <label for="fecha">FECHA<span class="required">*</span></label>
                                            <input type='date' id="fecha" name="fecha" class="form-control" required="Campo requerido" />
                                        </div>
                                        <div id="fhDev" class="col-md-6 col-sm-12 col-xs-12 form-group">
                                            <label for="fecha">FECHA DEVOLUCIÓN<span class="required">*</span></label>
                                            <input type='date' id="fhCompromiso" name="fhCompromiso" class="form-control" />
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group">
                                            <h2><b>DOCUMENTOS</b></h2>
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="padron" name="revision[]" value="0" class="js-switch" checked /> Padrón
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="permiso" name="revision[]" value="1" class="js-switch" checked /> Permiso circulación
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="revision" name="revision[]" value="2" class="js-switch" checked /> Revisión técnica
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="seguro" name="revision[]" value="3" class="js-switch" checked /> Seguro obligatorio
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="combustible" name="revision[]" value="4" class="js-switch" checked /> Tarjeta combustible
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <h2><b>ELEMENTOS Y ACCESORIOS</b></h2>
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="selloverde" name="revision[]" value="5" class="js-switch" checked /> Sello Verde
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="tag" name="revision[]" value="6" class="js-switch" checked /> TAG
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="radio" name="revision[]" value="7" class="js-switch" checked /> Radio
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="parasoles" name="revision[]" value="8" class="js-switch" checked /> Parasoles
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="gata" name="revision[]" value="9" class="js-switch" checked /> Gata y llave
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="rerueda" name="revision[]" value="10" class="js-switch" checked /> Rueda de repuesto
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="luces" name="revision[]" value="11" class="js-switch" checked /> Luces
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="frenos" name="revision[]" value="12" class="js-switch" checked /> Frenos
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="odometro" name="revision[]" value="13" class="js-switch" checked /> Odómetro
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="velocimetro" name="revision[]" value="14" class="js-switch" checked /> Velocímetro
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="indicombus" name="revision[]" value="15" class="js-switch" checked /> Indicador de combustible
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="cambios" name="revision[]" value="16" class="js-switch" checked /> Palanca de cambios
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="tapaestan" name="revision[]" value="17" class="js-switch" checked /> Tapa Estanque
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="direccion" name="revision[]" value="18" class="js-switch" checked /> Dirección
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="fremano" name="revision[]" value="19" class="js-switch" checked /> Freno de mano
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="intermitente" name="revision[]" value="20" class="js-switch" checked /> Intermitente
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="limparabrisas" name="revision[]" value="21" class="js-switch" checked /> Limpia Parabrisas
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="asientos" name="revision[]" value="22" class="js-switch" checked /> Asientos
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="pisos" name="revision[]" value="23" class="js-switch" checked /> Pisos
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="niveles" name="revision[]" value="24" class="js-switch" checked /> Niveles
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="parabrisas" name="revision[]" value="25" class="js-switch" checked /> Parabrisas
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="luneta" name="revision[]" value="26" class="js-switch" checked /> Luneta
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="puertas" name="revision[]" value="27" class="js-switch" checked /> Puertas
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="alzavidrios" name="revision[]" value="28" class="js-switch" checked /> Alzavidrios
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="cenicero" name="revision[]" value="29" class="js-switch" checked /> Cenicero
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="jaula" name="revision[]" value="30" class="js-switch" checked /> Jaula
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="motor" name="revision[]" value="31" class="js-switch" checked /> Motor
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="gps" name="revision[]" value="32" class="js-switch" checked /> GPS
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                                <label for="observaciones">Observaciones adicionales</label>
                                                <textarea type="text" id="observaciones" name="observaciones" class="resizable_textarea form-control" style="text-transform: uppercase"></textarea>
                                            </div>
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


                            <div id="formulariodevvehiculo" class="x_content">
                                <br />
                                <div class="col-md-12 center-margin">
                                    <form class="form-horizontal form-label-left" id="formulariodev" name="formulariodev">
                                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                            <label>VEHÍCULO</label>
                                            <input type="hidden" id="iddevasigvehi" name="idasigvehi" class="form-control">
                                            <input type="hidden" id="idempleadoasig" name="idempleadoasig" class="form-control">
                                            <input type="hidden" id="iddevvehiculo" name="iddevvehiculo" class="form-control">
                                            <input type="text" id="vehiculo" name="vehiculo" class="form-control" disabled="true">
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                            <label>EMPLEADO</label>
                                            <input type="hidden" id="iddevempleado" name="idempleado" class="form-control">
                                            <input type="text" id="empleado" name="empleado" class="form-control" disabled="true">
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                            <label>KILOMETRAJE <span class="required">*</span></label>
                                            <input type="number" id="kilometraje" name="kilometraje" class="form-control" required="Campo requerido" min="0" max="999999">
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                            <label for="fecha">FECHA<span class="required">*</span></label>
                                            <input type='date' id="fecha" name="fecha" class="form-control" required="Campo requerido" />
                                        </div>

                                        <div class="form-group">
                                            <h2><b>DOCUMENTOS</b></h2>
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="padron" name="revision[]" value="0" class="js-switch" checked /> Padrón
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="permiso" name="revision[]" value="1" class="js-switch" checked /> Permiso circulación
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="revision" name="revision[]" value="2" class="js-switch" checked /> Revisión técnica
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="seguro" name="revision[]" value="3" class="js-switch" checked /> Seguro obligatorio
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="combustible" name="revision[]" value="4" class="js-switch" checked /> Tarjeta combustible
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <h2><b>ELEMENTOS Y ACCESORIOS</b></h2>
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="selloverde" name="revision[]" value="5" class="js-switch" checked /> Sello Verde
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="tag" name="revision[]" value="6" class="js-switch" checked /> TAG
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="radio" name="revision[]" value="7" class="js-switch" checked /> Radio
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="parasoles" name="revision[]" value="8" class="js-switch" checked /> Parasoles
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="gata" name="revision[]" value="9" class="js-switch" checked /> Gata y llave
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="rerueda" name="revision[]" value="10" class="js-switch" checked /> Rueda de repuesto
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="luces" name="revision[]" value="11" class="js-switch" checked /> Luces
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="frenos" name="revision[]" value="12" class="js-switch" checked /> Frenos
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="odometro" name="revision[]" value="13" class="js-switch" checked /> Odómetro
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="velocimetro" name="revision[]" value="14" class="js-switch" checked /> Velocímetro
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="indicombus" name="revision[]" value="15" class="js-switch" checked /> Indicador de combustible
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="cambios" name="revision[]" value="16" class="js-switch" checked /> Palanca de cambios
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="tapaestan" name="revision[]" value="17" class="js-switch" checked /> Tapa Estanque
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="direccion" name="revision[]" value="18" class="js-switch" checked /> Dirección
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="fremano" name="revision[]" value="19" class="js-switch" checked /> Freno de mano
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="intermitente" name="revision[]" value="20" class="js-switch" checked /> Intermitente
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="limparabrisas" name="revision[]" value="21" class="js-switch" checked /> Limpia Parabrisas
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="asientos" name="revision[]" value="22" class="js-switch" checked /> Asientos
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="pisos" name="revision[]" value="23" class="js-switch" checked /> Pisos
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="niveles" name="revision[]" value="24" class="js-switch" checked /> Niveles
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="parabrisas" name="revision[]" value="25" class="js-switch" checked /> Parabrisas
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="luneta" name="revision[]" value="26" class="js-switch" checked /> Luneta
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="puertas" name="revision[]" value="27" class="js-switch" checked /> Puertas
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="alzavidrios" name="revision[]" value="28" class="js-switch" checked /> Alzavidrios
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="cenicero" name="revision[]" value="29" class="js-switch" checked /> Cenicero
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="jaula" name="revision[]" value="30" class="js-switch" checked /> Jaula
                                                    </label>
                                                </div>
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="motor" name="revision[]" value="31" class="js-switch" checked /> Motor
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" id="gps" name="revision[]" value="32" class="js-switch" checked /> GPS
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                                <label for="observaciones">OBSERVACIONES</label>
                                                <textarea type="text" id="observaciones" name="observaciones" class="resizable_textarea form-control" style="text-transform: uppercase"></textarea>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <button class="btn btn-primary" type="button" id="btnCancelar" onclick="cancelarform()">Cancelar</button>
                                                <button class="btn btn-primary" type="reset" id="btnLimpiar" onclick="limpiardev()">Limpiar</button>
                                                <button class="btn btn-success" type="submit" id="btnGuardardev">Agregar</button>
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

        <!-- Modal -->
        <div class="modal fade" id="modalPrestamo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title" id="">Pr&eacute;stamo de Veh&iacute;culo</h4>
                    </div>
                    <form id="formRegistroPrestamo" name="formRegistroPrestamo">
                        <input type="hidden" name="idprestamo" id="idprestamo">
                        <input type="hidden" name="id_asigvehi" id="id_asigvehi">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="id_empleado">Empleado</label>
                                <select class="form-control selectpicker" data-live-search="true" id="id_empleado" name="id_empleado" required="Campo requerido"></select>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fhCompromiso">Fecha devolución</label>
                                <input type="datetime-local" class="form-control" name="fhCompromiso" id="fhCompromiso" required="required">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" id="btnGuardarModal">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="ModalInhabilitar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="Inhabilitarform" class="form-horizontal calender" role="form" enctype="multipart/form-data">
                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">INHABILITAR ASIGNACIÓN DE VEHÍCULO</h4>
                        </div>
                        <div class="modal-body">
                            <div id="testmodal" style="padding: 5px 10px;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input type="hidden" name="idasigvehiculo" id="idasigvehiculo">
                                        <input type="hidden" name="idempleadoveh" id="idempleadoveh">
                                        <label>MOTIVO DEVOLUCIÓN</label><span class="required">*</span>
                                        <select class="form-control selectpicker" data-live-search="true" id="motivoInhab" name="motivoInhab" required="required">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" id="divMotivo" name="divMotivo">
                                    <div class="col-sm-12">
                                        <label>TIPO SERVICIO</label><span class="required">*</span>
                                        <select class="form-control selectpicker" data-live-search="true" id="tipoMant" name="tipoMant">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" id="divMotivo2" name="divMotivo2">
                                    <div class="col-sm-6">
                                        <label>FECHA SOLICITUD</label><span class="required">*</span>
                                        <input type='date' id="fhSolicitud" name="fhSolicitud" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>KMS. ACTUAL</label><span class="required">*</span>
                                        <input type='number' id="kmsActual" name="kmsActual" class="form-control" min="0" max="99999999999" onKeyPress="if(this.value.length==11) return false;">
                                    </div>
                                </div>
                                <div class="form-group" id="divMotivo3" name="divMotivo3">
                                    <div class="col-sm-12">
                                        <label>COMENTARIOS</label>
                                        <textarea class="form-control" style="height:50px; width:100%; text-transform: uppercase; resize:none;" id="comentariosServ" name="comentariosServ" placeholder="COMENTARIOS" maxlength="200"></textarea>
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

        <div id="btnInhab" data-toggle="modal" data-target="#ModalInhabilitar"></div>


    <?php
    } else {
        require 'nopermiso.php';
    }
    require 'footer.php';
    ?>
    <script src="../public/build/js/jspdf.min.js"></script>
    <script src="../public/build/js/jspdf.plugin.autotable.js"></script>
    <script src="../public/build/js/jsPDFcenter.js"></script>
    <script type="text/javascript" src="scripts/asignacionvehiculo.js"></script>
<?php
}
ob_end_flush();
?>