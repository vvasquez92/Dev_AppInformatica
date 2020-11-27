<?php
ob_start();
session_start();

if ( !isset( $_SESSION["nombre"] ) ) {
  header( "Location:login.php" );
} else {

  require 'header.php';

  if ( $_SESSION['administrador'] == 1 || $_SESSION['vehiculos'] == 1 ) {

?>

    <!-- page content -->
    <div class="right_col" role="main">
      <div class="">

        <div class="clearfix"></div>
        <input type="hidden" id="administrador" name="administrador" value=<?php echo $_SESSION['administrador'] ?>>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>VEHÍCULOS</h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-tooltip="tooltip" title="Operaciones" role="button" aria-expanded="false"><i class="fa fa-cog"></i></a>
                    <ul class="dropdown-menu" role="menu">
                      <li><a id="op_agregar" onclick="mostarform( true )">AGREGAR</a>
                      </li>
                      <li><a id="op_listar" onclick="mostarform( false )">LISTAR</a>
                      </li>
                      <li><a id="op_hist" onclick="mostarform( 99 )">HISTÓRICO</a>
                      </li>
                    </ul>
                  </li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div id="listadovehiculos" class="x_content">

                <table id="tblvehiculos" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th></th>
                      <th>VEHÍCULO</th>
                      <th>TIPO</th>
                      <th>AÑO</th>
                      <th>PATENTE</th>
                      <th>KILOMETRAJE</th>
                      <th>GPS</th>
                      <th>DISPONIBLE</th>
                      <th>ESTADO</th>
                      <th>CONDICIÓN</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>

              <div id="listadovehiculoshist" class="x_content">

                <table id="tblvehiculoshist" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th></th>
                      <th>VEHÍCULO</th>
                      <th>TIPO</th>
                      <th>AÑO</th>
                      <th>PATENTE</th>
                      <th>KILOMETRAJE</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>

              <div id="formulariovehiculos" class="x_content">
                <br />

                <div class="col-md-12 center-margin">
                  <form class="form-horizontal form-label-left" id="formformularioVehiculos" name="formformularioVehiculos">
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>TIPO</label><span class="required">*</span>
                      <input type="hidden" id="idvehiculo" name="idvehiculo" class="form-control">
                      <select class="form-control selectpicker" data-live-search="true" id="tvehiculo" name="tvehiculo" required="required">
                      </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>MARCA</label><span class="required">*</span>
                      <select class="form-control selectpicker" data-live-search="true" id="idmarca" name="idmarca" required="required">
                      </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>MODELO</label><span class="required">*</span>
                      <select class="form-control selectpicker" data-live-search="true" id="idmodelo" name="idmodelo" required="required">
                      </select>
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-6 form-group">
                      <label>AÑO</label><span class="required">*</span>
                      <input type="number" class="form-control" name="ano" id="ano" required="required" min="1900">
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-6 form-group">
                      <label>KILOMETRAJE</label><span class="required">*</span>
                      <input type="number" class="form-control" name="kilometraje" id="kilometraje" required="required" min="0">
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>PATENTE</label><span class="required">*</span>
                      <input type="text" class="form-control" name="patente" id="patente" required="required" style="text-transform: uppercase;" maxlength="9" >
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>SERIAL MOTOR</label>
                      <input type="text" class="form-control" name="serialmotor" id="serialmotor" maxlength="45">
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>SERIAL CARROCERÍA</label>
                      <input type="text" class="form-control" name="serialcarroceria" id="serialcarroceria" maxlength="45">
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>ESTADO</label><span class="required">*</span>
                      <select class="form-control selectpicker" data-live-search="true" id="estado" name="estado" required="required">
                        <option value="" selected disabled>SELECCIONE ESTADO</option>
                        <option value="1">NUEVO</option>
                        <option value="0">USADO</option>
                      </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>FECHA DE REVISIÓN DE GASES</label>
                        <input type='date' id="gases" name="gases" class="form-control" placeholder="" />                        
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>FECHA DE REVISIÓN TÉCNICA</label>
                        <input type='date' id="tecnica" name="tecnica" class="form-control" placeholder="" />                        
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>FECHA PERMISO DE CIRCULACIÓN</label>
                        <input type='date' id="circulacion" name="circulacion" class="form-control" placeholder="" />                        
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>DISPOSITIVO GPS</label><span class="required">*</span>
                      <select id="tieneGPS" name="tieneGPS" class="selectpicker form-control" required="required">
                        <option value="" disabled>SELECCIONE</option>
                        <option value="1">SI</option>
                        <option value="0">NO</option>
                      </select>
                    </div>
                    <div id="divGPS" name="divGPS" class="col-md-12 col-sm-12 col-xs-12 form-group">
                      <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                        <label>FECHA DE INSTALACIÓN GPS</label><span class="required">*</span>
                          <input type='date' id="instalaGPS" name="instalaGPS" class="form-control" placeholder="" />                          
                      </div>
                      <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                        <label>PROVEEDOR GPS</label><span class="required">*</span>
                        <select class="form-control selectpicker" data-live-search="true" id="sProvGpsNew" name="sProvGpsNew">
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                      <label for="nedificios">OBSERVACIONES</label>
                      <textarea type="text" id="observaciones" name="observaciones" class="resizable_textarea form-control" style="text-transform: uppercase;"></textarea>
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
        </div>
      </div>
    </div>
    <!-- /page content -->


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
          <form id="formPlazoDocumentosVehiculo" name="formPlazoDocumentosVehiculo">
            <input type="hidden" name="id_vehiculo" id="id_vehiculo">
            <div class="modal-body">
              <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                <label>FECHA DE REVISIÓN DE GASES</label>
                <div class='col-md-12 col-sm-12 col-xs-12 input-group date form_datetime'>
                  <input type='date' id="doc_gases" name="doc_gases" class="form-control" placeholder="" />
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                <label>FECHA DE REVISIÓN TÉCNICA</label>
                <div class='col-md-12 col-sm-12 col-xs-12 input-group date form_datetime'>
                  <input type='date' id="doc_tecnica" name="doc_tecnica" class="form-control" placeholder="" />
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                <label>FECHA DE PERMISO DE CIRCULACIÓN</label>
                <div class='col-md-12 col-sm-12 col-xs-12 input-group date form_datetime'>
                  <input type='date' id="doc_circulacion" name="doc_circulacion" class="form-control" placeholder="" />
                </div>
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

    <div id="modalGPS" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="min-width:60%;">
        <form id="GPSform" class="form-horizontal calender" role="form" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title" id="myModalLabel">DISPOSITIVO GPS</h4>
            </div>
            <div class="modal-body">
              <div id="testmodal" style="padding: 5px 10px;">
                <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <label>FECHA INSTALACIÓN</label>
                    <input type='date' id="fhInstalacionGPS" name="fhInstalacionGPS" class="form-control" required="Campo requerido" />
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <label>PROVEEDOR</label>
                    <select class="form-control selectpicker" data-live-search="true" id="sProvGps" name="sProvGps" required="Campo requerido">
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default antoclose" data-dismiss="modal">CANCELAR</button>
              <button type="submit" id="enviar" class="btn btn-primary">GUARDAR</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div id="btnDispGPS" data-toggle="modal" data-target="#modalGPS"></div>

    <div id="modalInspeccion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="min-width:60%;">
        <form id="Inspeccionform" class="form-horizontal calender" role="form" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title" id="myModalLabel">INSPECCIÓN DE RUTINA</h4>
            </div>
            <div class="modal-body">
              <div id="testmodal" style="padding: 5px 10px;">
                <div class="form-group">
                  <div class="col-sm-12 col-sm-12 col-xs-12">
                    <label>KILOMETRAJE ACTUAL</label>
                    <input type="hidden" id="idveh" name="idveh" class="form-control">
                    <input type='number' id="txtKmsActual" name="txtKmsActual" class="form-control" required="Campo requerido" min=0 max=999999 onKeyPress="if(this.value.length==6) return false;"/>
                  </div>
                  <div class="col-sm-12 col-sm-12 col-xs-12">
                    <label>MOTIVO</label>
                    <textarea type="text" id="txaMotivo" name="txaMotivo" class="resizable_textarea form-control" style="text-transform: uppercase;" maxlength="200" required="Campo requerido"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default antoclose" data-dismiss="modal">CANCELAR</button>
              <button type="submit" id="enviar" class="btn btn-primary">GUARDAR</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div id="btnInspeccion" data-toggle="modal" data-target="#modalInspeccion"></div>

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
  <script type="text/javascript" src="scripts/vehiculo.js"></script>
<?php
}
ob_end_flush();
?>