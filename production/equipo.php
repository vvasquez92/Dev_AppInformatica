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
                <h2>Moviles</h2>
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
              <div id="listadomoviles" class="x_content">

                <table id="tblmoviles" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th></th>
                      <th>EQUIPO</th>
                      <th>IMEI</th>
                      <th>SERIAL</th>
                      <th>DISPONIBLE</th>
                      <th>ESTADO</th>
                      <th>CONDICION</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                <br><br>
                <label id="lblAsignados" name="lblAsignados" class="label bg-red"></label>
                <label id="lblLibres" name="lblLibres" class="label bg-green"></label>
                <label id="lblUsados" name="lblUsados" class="label bg-red"></label>
                <label id="lblNuevos" name="lblNuevos" class="label bg-green"></label>
              </div>

              <div id="formularimoviles" class="x_content">
                <br />

                <div class="col-md-12 center-margin">
                  <form class="form-horizontal form-label-left" id="formulario" name="formulario">
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                      <label>TIPO</label>
                      <input type="hidden" id="idequipo" name="idequipo" class="form-control">
                      <select class="form-control selectpicker" data-live-search="false" id="iddetalle" name="iddetalle" required="required"></select>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                      <label>IMEI</label>
                      <input type="text" class="form-control" name="imei" id="imei" required="">
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                      <label>SERIAL</label>
                      <input type="text" class="form-control" name="serial" id="serial" required="">
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                      <label>SERIAL CAJA</label>
                      <input type="text" class="form-control" name="caja" id="caja">
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                      <label>ESTADO</label>
                      <select class="form-control selectpicker" data-live-search="false" id="estado" name="estado" required="required">
                        <option value="" selected disabled>SELECCIONE ESTADO</option>
                        <option value="1">NUEVO</option>
                        <option value="0">USADO</option>
                      </select>
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
  <script type="text/javascript" src="scripts/equipo.js"></script>
<?php
}
ob_end_flush();
?>