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
                <h2>GESTIÓN DE VEHÍCULOS</h2>

                <ul class="nav navbar-right panel_toolbox">
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-tooltip="tooltip" title="Operaciones" role="button" aria-expanded="false"><i class="fa fa-cog"></i></a>
                    <ul class="dropdown-menu" role="menu">
                      <li><a id="op_historico" onclick="muestraHistorico()">HISTÓRICO</a>
                      </li>
                      <li><a id="op_actual" onclick="mostrarform(false)">LISTAR</a>
                      </li>
                    </ul>
                  </li>
                </ul>

                <ul class="nav navbar-right panel_toolbox">
                  <button type="button" id="boton_regresar" onclick="mostrarform(false);" class="btn btn-primary">Regresar</button>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div id="listadovehiculosgestion" class="x_content">
                <table id="tblvehiculosgestion" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th></th>
                      <th>TIPO DE GESTIÓN</th>
                      <th>VEHÍCULO</th>
                      <th>AÑO</th>
                      <th>PATENTE</th>
                      <th>SERIAL MOTOR</th>
                      <th>ÚLTIMA GESTIÓN</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>

              <div id="listadovehiculosgestionHist" class="x_content">
                <table id="tblvehiculosgestionHist" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th></th>
                      <th>TIPO DE GESTIÓN</th>
                      <th>VEHÍCULO</th>
                      <th>AÑO</th>
                      <th>PATENTE</th>
                      <th>SERIAL MOTOR</th>
                      <th>FECHA CREACIÓN</th>
                      <th>ÚLTIMA GESTIÓN</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>


              <div class="row" id="verGestion">
                <div class="col-md-12">
                  <div class="x_panel">

                    <div class="x_content">

                      <!-- start project-detail sidebar -->
                      <div class="col-md-12 col-sm-12 col-xs-12">

                        <section class="panel">

                          <div class="x_title">
                            <h3>Datos del Veh&iacute;culo</h3>
                            <div class="clearfix"></div>
                          </div>
                          <div class="panel-body">

                            <div class="project_detail">
                              <div class="col-md-4 col-sm-4 col-xs-12">
                                <p class="title">Veh&iacute;culo</p>
                                <p id="vehiculo"></p>
                              </div>
                              <div class="col-md-2 col-sm-4 col-xs-12">
                                <p class="title">Tipo</p>
                                <p id="tipo"></p>
                              </div>
                              <div class="col-md-3 col-sm-4 col-xs-12">
                                <p class="title">Año</p>
                                <p id="anio"></p>
                              </div>
                              <div class="col-md-3 col-sm-4 col-xs-12">
                                <p class="title">Patente</p>
                                <p id="patente"></p>
                              </div>
                              <div class="col-md-4 col-sm-4 col-xs-12">
                                <p class="title">Kilometraje</p>
                                <p id="kilometraje"></p>
                              </div>
                              <div class="col-md-2 col-sm-4 col-xs-12">
                                <p class="title">Disponible</p>
                                <p id="disponible"></p>
                              </div>
                              <div class="col-md-3 col-sm-4 col-xs-12">
                                <p class="title">Estado</p>
                                <p id="estado"></p>
                              </div>
                              <div class="col-md-3 col-sm-4 col-xs-12">
                                <p class="title">Condici&oacute;n</p>
                                <p id="condicion"></p>
                              </div>
                            </div>
                            <br />
                          </div>
                        </section>

                      </div>
                      <!-- end project-detail sidebar -->
                      <div class="x_title">
                        <h3>Detalles de la Gesti&oacute;n</h3>
                        <div class="clearfix"></div>
                      </div>
                      <div class="col-md-12 col-sm-12 col-xs-12" style="text-align:center;">

                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <span class="title"> Tipo de Gesti&oacute;n </span><br>
                          <b><span class="value text-success" id="tipo_gestion"> </span></b>
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <span class="title"> Fecha de Creaci&oacute;n </span><br>
                          <b><span class="value text-success" id="fecha_creacion_gestion"> </span></b>
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <span class="title"> Última Gestión </span><br>
                          <b><span class="value text-success" id="fecha_actualizacion_gestion"> </span></b>
                        </div>
                      </div>
                      <div>
                        <!-- end of user messages -->
                        <!--<ul class="messages" id="listaGestion"> </ul>-->
                        <div id="listadoGestiones" class="x_content">
                          <table id="tblListadoGestiones" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                <th style="max-width:15% !important;">Colaborador</th>
                                <th style="max-width:15% !important;">Estado de la gestión</th>
                                <th style="max-width:55% !important;">Descripción</th>
                                <th style="max-width:15% !important;">Fecha</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                        <!-- end of user messages -->
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
            <input type="hidden" name="idgestion_ve" id="idgestion_ve" value="">
            <input type="hidden" name="idvehiculo" id="idvehiculo" value="">
            <div class="modal-body">
              <div class="form-group">
                <label for="titulo">Estado de la gesti&oacute;n</label>
                <input type="text" class="form-control" id="titulo" name="titulo" style="text-transform: uppercase;" placeholder="Ingrese el estado de la gestión" required="required" maxlength="100">
              </div>
              <div class="form-group">
                <label for="descripcion">Descripci&oacute;n</label>
                <textarea id="descripcion" name="descripcion" class="form-control" style="text-transform: uppercase;" placeholder="Ingrese la descripción" required="required"></textarea>
              </div>
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="check_finGestion" name="check_finGestion">
                <label class="form-check-label" for="check_finGestion">Finalizar la Gesti&oacute;n</label>
              </div>
              <div class="form-group" style="display: none" id="div_estado_final">
                <label for="estado_final">Estado final del veh&iacute;culo después de esta gesti&oacute;n</label>
                <select class="form-control" id="estado_final" name="estado_final">
                  <option value="">
                    <--Seleccione-->
                  </option>
                  <option value="0">Vehículo Normalizado</option>
                  <option value="1">Mantención</option>
                  <option value="2">Reparación</option>
                  <option value="3">Siniestro</option>
                  <option value="4">Robo</option>
                </select>
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
  <script type="text/javascript" src="scripts/gestionvehiculo.js"></script>
<?php
}
ob_end_flush();
?>