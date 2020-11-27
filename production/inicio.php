<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location:login.php");
} else {

  require 'header.php';


?>
  <!-- Contenido -->
  <div class="right_col" role="main">
    <div class="">
      <div class="page-title">
        <div class="title" id="titulo">
          <h3><b>APP SERVICIOS E INFORMATICA</b></h3>
          <h3>CONTROL DE GESTION</h3>
          <h3>DPTO. SERVICIOS E INFORMATICA</h3>
        </div>
      </div>

      <div class="clearfix"></div>

      <div id="firma">
        <div role="main">
          <div class="row">
            <form id="firmaForm" class="form-horizontal calender" role="form" enctype="multipart/form-data">
              <div class="form-group">
                <div class="col-md-11 col-sm-12 col-xs-12 form-group">
                  <label for="firma">POR FAVOR REGISTRE SU FIRMA DIGITAL</label>
                  <div class="input-group">
                    <input type="hidden" name="firma" id="firma">
                    <input type="text" class="form-control" name="firmavali" id="firmavali" style="pointer-events: none" required="required" readonly="readonly">
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Opciones <span class="caret"></span></button>
                      <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        <li><a onclick="fijarfirma()">Validar</a></li>
                        <li class="divider"></li>
                        <li><a onclick="borrarfirma()">Borrar firma</a></li>
                      </ul>
                      </ul>
                    </div>
                  </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group" id="firmapad" name="firmapad">
                  <div class="well">
                    <canvas id="firmafi" id="firmafi" class="firmafi" style="border: 2px dashed #888; width: 100%; height: 300px;"></canvas>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-success" type="submit" id="btnGuardar">GUARDAR</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
  <!-- /Fin Contenido -->

  <?php
  require 'footer.php';
  ?>
  <script type="text/javascript" src="../public/build/js/instascan.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
  <script type="text/javascript" src="scripts/inicio.js"></script>
<?php
}
ob_end_flush();
?>