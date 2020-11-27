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
                <h2>Empleados</h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-tooltip="tooltip" title="Operaciones" role="button" aria-expanded="false"><i class="fa fa-cog"></i></a>
                    <ul class="dropdown-menu" role="menu">
                      <li><a id="op_agregar" onclick="mostarform(true)">AGREGAR</a>
                      </li>
                      <li><a id="op_listar" onclick="mostarform(false)">LISTAR</a>
                      </li>
                      <li><a id="op_historico" onclick="historico(true)">HISTÓRICO</a>
                      </li>
                    </ul>
                  </li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div id="listadoempleados" class="x_content">

                <table id="tblempleados" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>Opciones</th>
                      <th>Nombre y Apellido</th>
                      <th>Documento Identidad</th>
                      <th>Cargo</th>
                      <th>Departamento</th>
                      <th>Oficina</th>
                      <th>Condición</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>

              <div id="listadoempleadoshistorico" class="x_content">

                <table id="tblempleadoshistorico" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>Opciones</th>
                      <th>Nombre y Apellido</th>
                      <th>Documento Identidad</th>
                      <th>Cargo</th>
                      <th>Departamento</th>
                      <th>Oficina</th>
                      <th>Condición</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>

              <div id="formularioempleados" class="x_content">
                <br />
                <form id="formulario" name="formulario" class="form-horizontal form-label-left input_mask">
                  <input type="hidden" name="imagen_cam" id="imagen_cam">
                  <input id="imagenactual" name="imagenactual" type="hidden">

                  <div class="row">
                    <div class="col-sm-6 col-md-6">
                      <div class="thumbnail" style="height: auto">
                        <canvas width="200" height="200" class="img-thumbnail" id="canvas"></canvas>
                        <div class="caption">
                          <p>
                            <button class="btn btn-danger" onclick="activar_camara();return false;">Activar Camara&nbsp;<i class="fa fa-camera"></i></button>
                          </p>
                          <p id="estado"></p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-6" style="display: none" id="div_video">
                      <div class="thumbnail" style="height: auto">
                        <video class="img-thumbnail" id="video" width="280" height="200"></video>
                        <div class="caption">
                          <p>
                            <button id="boton" class="btn btn-primary" onclick="tomar_foto();return false;">Tomar foto</button>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                    <label>TIPO DE DOCUMENTO</label>
                    <select class="form-control selectpicker" data-live-search="true" id="tipo_documento" name="tipo_documento" required="required">
                      <option value="" selected disabled>Tipo de Documento</option>
                      <option value="RUT">RUT</option>
                      <option value="P">Pasaporte</option>
                      <option value="O">Otro</option>
                    </select>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                    <label>NUMERO DE DOCUMENTO</label>
                    <input type="text" class="form-control has-feedback-left" id="num_documento" name="num_documento" placeholder="Numero de Documento" required="required" maxlength="40">
                    <span class="fa fa-id-card form-control-feedback left" aria-hidden="true"></span>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                    <label>NOMBRES</label>
                    <input type="hidden" class="form-control has-feedback-left" id="idempleado" name="idempleado">
                    <input type="text" class="form-control has-feedback-left" id="nombre" name="nombre" placeholder="Nombres" required="">
                    <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                    <label>APELLIDOS</label>
                    <input type="text" class="form-control has-feedback-left" id="apellido" name="apellido" placeholder="Apellidos" required="">
                    <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                  </div>                  

                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                    <label>FECHA DE VENCIMIENTO DE CARNET</label>
                    <input type="date" class="form-control has-feedback-left" id="vencimiento_carnet" name="vencimiento_carnet" placeholder="Fecha de Vencimiento del carnet de identidad">
                    <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                    <label>FECHA DE VENCIMIENTO DE LA LICENCIA</label>
                    <input type="date" class="form-control has-feedback-left" id="licencia" name="licencia" placeholder="Fecha de vencimiento de la licencia">
                    <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                    <label>DIRECCION</label>
                    <input type="text" class="form-control has-feedback-left" id="direccion" name="direccion" placeholder="Direccion">
                    <span class="fa fa-map-marker form-control-feedback left" aria-hidden="true"></span>
                  </div>

                  <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                    <label>REGION</label>
                    <select class="form-control selectpicker" data-live-search="true" id="idregiones" name="idregiones" required="required">
                      <option value="" selected disabled>Region</option>
                    </select>
                  </div>

                  <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                    <label>COMUNA</label>
                    <select class="form-control selectpicker" data-live-search="true" id="idcomunas" name="idcomunas" required="required">
                      <option value="" selected disabled>Comuna</option>
                    </select>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                    <label>TELEFONO MOVIL</label>
                    <input type="text" id="movil" name="movil" class="form-control has-feedback-left" placeholder="Telefono Movil" data-inputmask="'mask' : '+56(9)9999-9999'">
                    <span class="fa fa-mobile form-control-feedback left" aria-hidden="true"></span>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                    <label>TELEFONO RESIDENCIAL</label>
                    <input type="text" id="residencial" name="residencial" class="form-control has-feedback-left" placeholder="Telefono Residencial" data-inputmask="'mask' : '+56(2)9999-9999'">
                    <span class="fa fa-mobile form-control-feedback left" aria-hidden="true"></span>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                    <label>EMAIL PERSONAL</label>
                    <input type="email" class="form-control has-feedback-left" id="email" name="email" placeholder="Email Personal">
                    <span class="fa fa-envelope-o form-control-feedback left" aria-hidden="true"></span>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                    <label>EMAIL CORPORATIVO</label>
                    <input type="email" class="form-control has-feedback-left" id="email_corporativo" name="email_corporativo" placeholder="Email Corporativo">
                    <span class="fa fa-envelope-o form-control-feedback left" aria-hidden="true"></span>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <label>FECHA DE NACIMIENTO</label>
                    <input type='date' id="fecha_nac" name="fecha_nac" class="form-control" placeholder="Fecha de Nacimiento" required="Campo requerido" />
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                    <label>OFICINA</label>
                    <select class="form-control selectpicker" data-live-search="true" id="idoficina" name="idoficina" required="required">
                    </select>
                  </div>

                  <div class="col-md-11 col-sm-11 col-xs-11 form-group">
                    <select class="form-control selectpicker" data-live-search="true" id="iddepartamento" name="iddepartamento" required="required">
                    </select>
                  </div>

                  <div class="col-md-1 col-sm-1 col-xs-1 form-group has-feedback">
                    <button type="button" onclick="agregarDepartamento();" class="btn glyphicon glyphicon-plus-sign" data-toggle="tooltip" data-placement="bottom" title="Agregar Departamento"></button>
                  </div>

                  <div class="col-md-11 col-sm-11 col-xs-11 form-group">
                    <select class="form-control selectpicker" data-live-search="true" id="idcargo" name="idcargo" required="required">
                      <option value="" selected disabled>Cargo</option>
                    </select>
                  </div>

                  <div class="col-md-1 col-sm-1 col-xs-1 form-group has-feedback">
                    <button type="button" data-toggle="modal" data-target="#modalFormCargo" class="btn glyphicon glyphicon-plus-sign" data-placement="bottom" title="Agregar Cargo"></button>
                  </div>

                  <div class="form-group">
                    <h2><b>Solicitudes</b></h2>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="">
                        <label>
                          <input type="checkbox" id="check_epp" name="check_epp" value="1" class="js-switch" /> EPP
                        </label>
                      </div>
                      <div class="">
                        <label>
                          <input type="checkbox" id="check_ti" name="check_ti" value="1" class="js-switch" /> T.I
                        </label>
                      </div>
                      <div class="form-group" style="display: none" id="div_ti">
                        <h2><b>TI</b></h2>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                          <div class="">
                            <label>
                              <input type="checkbox" id="check_celular" name="check_celular" value="0" class="js-switch" /> CELULAR
                            </label>
                          </div>
                          <div class="">
                            <label>
                              <input type="checkbox" id="check_tarjetaid" name="check_tarjetaid" value="1" class="js-switch" /> TAJETA ID
                            </label>
                          </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                          <div class="">
                            <label>
                              <input type="checkbox" id="check_email" name="check_email" value="2" class="js-switch" /> EMAIL
                            </label>
                          </div>
                          <div class="">
                            <label>
                              <input type="checkbox" id="check_tarjeta" name="check_tarjeta" value="3" class="js-switch" /> TARJETA
                            </label>
                          </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                          <div class="">
                            <label>
                              <input type="checkbox" id="check_notebook" name="check_notebook" value="4" class="js-switch" /> NOTEBOOK
                            </label>
                          </div>
                        </div>
                      </div>

                      <div class="">
                        <label>
                          <input type="checkbox" id="check_vehiculo" name="check_vehiculo" value="1" class="js-switch" /> VEHICULO
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="clearfix"></div>
                  <div class="ln_solid"></div>

                  <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <button class="btn btn-primary" type="button" id="btnCancelar" onclick="cancelarform()">Cancelar</button>
                      <button class="btn btn-primary" type="reset" id="btnLimpiar" onclick="limpiar()">Limpiar</button>
                      <button class="btn btn-success" type="submit" id="btnGuardar">Agregar</button>
                    </div>
                  </div>
                </form>
              </div>


              <div class="row" id="FichaEmpleado" style="display: none">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Ficha del Empleado</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                      <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="card">
                          <img src="" alt="" id="imagen_empleado" width="300" height="300">
                          <div class="card-body">
                            <h5 class="card-title">&nbsp;</h5>
                            <p class="card-text"></p>
                            <a href="#" class="btn btn-primary">Generar PDF</a>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-8 col-sm-8 col-xs-12">

                        <div class="col-md-12 col-sm-12 col-xs-12" style="border:0px solid #e5e5e5;">
                          <div class="col-md-6 col-sm-6 col-xs-12" style="border:0px solid #e5e5e5;">
                            <h3 class="prod_title">Datos Personales</h3>
                            <dl>
                              <dt>Nombres</dt>
                              <dd id="nombre_empleado"></dd><br>
                              <dt>Apellidos</dt>
                              <dd id="apellido_empleado"></dd><br>
                              <dt>Tipo de Documento</dt>
                              <dd id="tipo_documento_empleado"></dd><br>
                              <dt>N&uacute;mero de Documento</dt>
                              <dd id="documento_empleado"></dd><br>
                            </dl>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-12" style="border:0px solid #e5e5e5;">
                            <h3 class="prod_title">&nbsp;</h3>
                            <dl>
                              <dt>Tel&eacute;fono M&oacute;vil</dt>
                              <dd id="movil_empleado"></dd><br>
                              <dt>Tel&eacute;fono Residencial</dt>
                              <dd id="residencial_empleado"></dd><br>
                              <dt>Email Personal</dt>
                              <dd id="email_empleado"></dd><br>
                              <dt>Direcci&oacute;n</dt>
                              <dd id="direccion_empleado"></dd>
                            </dl>
                          </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12" style="border:0px solid #e5e5e5;">
                          <h3 class="prod_title">Datos Corporativos</h3>
                          <dl>
                            <dt>Departamento</dt>
                            <dd id="departamento_empleado"></dd><br>
                            <dt>Cargo</dt>
                            <dd id="cargo_empleado"></dd><br>
                            <dt>Email Corporativo</dt>
                            <dd id="correo_corporativo"></dd>
                          </dl>
                        </div>
                      </div>

                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                            <h2><small>Implemento Asignados</small></h2>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">

                            <!-- start accordion -->
                            <div class="accordion" id="accordion1" role="tablist" aria-multiselectable="true">
                              <div class="panel">
                                <a class="panel-heading" role="tab" id="heading1" data-toggle="collapse" data-parent="#accordion1" href="#collapse1" aria-expanded="true" aria-controls="collapseOne">
                                  <h4 class="panel-title"><i class="fa fa-mobile"></i>&nbsp;Tel&eacute;fono M&oacute;vil Asignado</h4>
                                </a>
                                <div id="collapse1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading1">
                                  <div class="panel-body">
                                    <table id="tbltabtelefono" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>
                                          <th>#</th>
                                          <th>MARCA - EQUIPO</th>
                                          <th>IMEI</th>
                                          <th>NUMERO</th>
                                          <th>FECHA DE ASIGNACION</th>
                                          <th>FECHA DE DEVOLUCION</th>
                                          <th>ESTADO</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                              <div class="panel">
                                <a class="panel-heading collapsed" role="tab" id="heading2" data-toggle="collapse" data-parent="#accordion1" href="#collapse2" aria-expanded="false" aria-controls="collapseTwo">
                                  <h4 class="panel-title"><i class="fa fa-car"></i>&nbsp;Vehículo Asignado</h4>
                                </a>
                                <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
                                  <div class="panel-body">
                                    <table id="tbltabvehiculo" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>
                                          <th>#</th>
                                          <th>MARCA - MODELO</th>
                                          <th>PATENTE</th>
                                          <th>FECHA DE ASIGNACION</th>
                                          <th>FECHA DE DEVOLUCION</th>
                                          <th>ESTADO</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                              <div class="panel">
                                <a class="panel-heading collapsed" role="tab" id="heading3" data-toggle="collapse" data-parent="#accordion1" href="#collapse3" aria-expanded="false" aria-controls="collapseThree">
                                  <h4 class="panel-title"><i class="fa fa-laptop"></i>&nbsp;Computador Asignado</h4>
                                </a>
                                <div id="collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3">
                                  <div class="panel-body">
                                    <table id="tbltabcomputador" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>
                                          <th>#</th>
                                          <th>MARCA - MODELO</th>
                                          <th>TIPO</th>
                                          <th>IP</th>
                                          <th>USUARIO</th>
                                          <th>CONTRASEÑA</th>
                                          <th>FECHA DE ASIGNACION</th>
                                          <th>FECHA DE DEVOLUCION</th>
                                          <th>ESTADO</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                              <div class="panel">
                                <a class="panel-heading collapsed" role="tab" id="heading4" data-toggle="collapse" data-parent="#accordion1" href="#collapse4" aria-expanded="false" aria-controls="collapseThree">
                                  <h4 class="panel-title"><i class="fa fa-id-card"></i>&nbsp;Tarjeta Asignada</h4>
                                </a>
                                <div id="collapse4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading4">
                                  <div class="panel-body">
                                    <table id="tbltabtarjeta" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>
                                          <th>#</th>
                                          <th>CODIGO</th>
                                          <th>NIVEL</th>
                                          <th>FECHA DE ASIGNACION</th>
                                          <th>FECHA DE DEVOLUCION</th>
                                          <th>ESTADO</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- end of accordion -->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /page content -->

    <!-- Modal -->
    <div class="modal fade" id="modalFormCargo" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">AGREGAR CARGO</h4>
          </div>

          <form role="form" id="formCargo" name="formCargo">
            <input type="hidden" id="idcargos" name="idcargos" value="">
            <div class="modal-body">
              <div class="form-group">
                <label for="id_departamento">Departamento <span class="required">*</span></label>
                <select class="form-control selectpicker" data-live-search="true" id="id_departamento" name="id_departamento" required="Campo requerido">
                  <option value="" selected disabled>
                    <--Seleccione Departamento-->
                  </option>
                </select>
              </div>
              <div class="form-group">
                <label for="nombre_cargo">Cargo<span class="required">*</span></label>
                <input type="text" class="form-control" id="nombre_cargo" name="nombre_cargo" aria-describedby="" placeholder="Ingrese el nombre" required="" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="clearfix"></div>

            <!-- Modal Footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" id="btnGuardarCargo">Agregar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Modal -->

    <!-- Modal -->
    <div class="modal fade" id="modalDocumentos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="">Registro de Documentos</h4>
          </div>
          <form id="formPlazoDocumentosEmpleado" name="formPlazoDocumentosEmpleado">
            <input type="hidden" name="id_empleado" id="id_empleado">
            <div class="modal-body">
              <div class="form-group">
                <label>FECHA DE VENCIMIENTO DEL CARNET DE IDENTIDAD</label>
                <div class='input-group date'>
                  <input type='date' id="doc_vencimiento_carnet" name="doc_vencimiento_carnet" class="form-control" placeholder="" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>
              <div class="form-group">
                <label>FECHA DE VENCIMIENTO DE LA LICENCIA DE CONDUCIR</label>
                <div class='input-group date'>
                  <input type='date' id="doc_licencia" name="doc_licencia" class="form-control" placeholder="" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary" id="btnGuardarModalDoc">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalCorreoCorporativo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
            <h4 class="modal-title" id="">Registro de Correo Corporativo</h4>
          </div>
          <form id="formCorreoCorporativo" name="formCorreoCorporativo">
            <input type="hidden" name="id_empl" id="id_empl">
            <div class="modal-body">
              <div class="form-group">
                <label>Correo Corporativo</label>
                <div class='input-group'>
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-envelope"></span>
                  </span>
                  <input type='email' id="doc_correo_corporativo" name="doc_correo_corporativo" class="form-control" placeholder="" />
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary" id="btnGuardarModalCorreo">Guardar</button>
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

  <script type="text/javascript" src="scripts/empleado.js"></script>
  <script type="text/javascript" src="scripts/captura.js"></script>
<?php
}
ob_end_flush();
?>