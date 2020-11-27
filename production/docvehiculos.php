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
                <h2>DOCUMENTACIÓN DE VEHÍCULOS</h2>

                <div class="clearfix"></div>
              </div>
              <div id="listadovehiculos" class="x_content">

                <table id="tblvehiculos" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th></th>
                      <th>VEHÍCULO</th>
                      <th>TIPO</th>
                      <th>PATENTE</th>
                      <th>DISPONIBLE</th>
                      <th>GASES</th>
                      <th>TÉCNICA</th>
                      <th>CIRCULACIÓN</th>
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
              <div class="form-group">
                <label>FECHA DE REVISIÓN DE GASES</label>
                  <input type='date' id="doc_gases" name="doc_gases" class="form-control" placeholder="" />
              </div>
              <div class="form-group">
                <label>FECHA DE REVISIÓN TÉCNICA</label>
                  <input type='date' id="doc_tecnica" name="doc_tecnica" class="form-control" placeholder="" />
              </div>
              <div class="form-group">
                <label>FECHA DE PERMISO DE CIRCULACIÓN</label>
                  <input type='date' id="doc_circulacion" name="doc_circulacion" class="form-control" placeholder="" />
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

    <div id="btnDocumentos" data-toggle="modal" data-target="#modalDocumentos"></div>

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
  <script type="text/javascript" src="scripts/docvehiculos.js"></script>
<?php
}
ob_end_flush();
?>