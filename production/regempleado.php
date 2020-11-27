<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Servicios e Informática</title>

    <link rel="icon" href="../public/favicon.ico" type="image/x-icon" />

    <!-- Bootstrap -->
    <link href="../public/build/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../public/build/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../public/build/css/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../public/build/css/green.css" rel="stylesheet">
    <!-- FORMS -->
    <link href="../public/build/css/prettify.min.css" rel="stylesheet">
    <link href="../public/build/css/select2.min.css" rel="stylesheet">
    <link href="../public/build/css/switchery.min.css" rel="stylesheet">
    <link href="../public/build/css/starrr.css" rel="stylesheet">
    <!-- bootstrap-select -->
    <link href="../public/build/css/bootstrap-select.min.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../public/build/css/daterangepicker.css" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="../public/build/css/bootstrap-datetimepicker.css" rel="stylesheet"> 
    <!-- bootstrap-progressbar -->
    <link href="../public/build/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../public/build/css/daterangepicker.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../public/build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-12 center_col" role="main">
          <div class="">

            <div class="clearfix"></div>
            <br />
            <div class="row">
                            <!-- form input mask -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Servicios generales e Informática - Formulario de registro - Empleado</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div id="formularioempleados" class="x_content">
                    <br />
                    <form id="formulario" name="formulario" class="form-horizontal form-label-left input_mask">

                      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" id="nombre" name="nombre" placeholder="Nombres" required="Campo requerido">
                        <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                      </div>

                      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" id="apellido" name="apellido" placeholder="Apellidos" required="Campo requerido">
                        <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                      </div>

                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                            <select class="form-control selectpicker" data-live-search="true" id="tipo_documento" name="tipo_documento" required="Campo requerido">
                                <option value="" selected disabled>Seleccione Tipo de Documento</option>
                                <option value="RUT">RUT</option>
                                <option value="P">Pasaporte</option>
                                <option value="O">Otro</option>
                            </select>
                      </div>

                      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" id="num_documento" name="num_documento" placeholder="Numero de RUT" data-inputmask="'mask' : '99.999.999-*'" required="Campo requerido">
                        <span class="fa fa-id-card form-control-feedback left" aria-hidden="true"></span>
                      </div>

                      <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                            <select class="form-control selectpicker" data-live-search="true" id="idregiones" name="idregiones" required="Campo requerido">       
                            </select>
                      </div>

                      <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                            <select class="form-control selectpicker" data-live-search="true" id="idcomunas" name="idcomunas" required="Campo requerido">
                                <option value="" selected disabled>Seleccione Comuna</option>
                            </select>
                      </div>

                      <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" id="direccion" name="direccion" placeholder="Direccion" required="Campo requerido">
                        <span class="fa fa-map-marker form-control-feedback left" aria-hidden="true"></span>
                      </div>

                      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="text" id="movil" name="movil" class="form-control has-feedback-left" placeholder="Telefono Movil" data-inputmask="'mask' : '+56(9)9999-9999'" required="Campo requerido">
                        <span class="fa fa-mobile form-control-feedback left" aria-hidden="true"></span>
                      </div>

                      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="text" id="residencial" name="residencial" class="form-control has-feedback-left" placeholder="Telefono Residencial" data-inputmask="'mask' : '+56(2)9999-9999'">
                        <span class="fa fa-mobile form-control-feedback left" aria-hidden="true"></span>
                      </div>

                      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="email" class="form-control has-feedback-left" id="email" name="email" placeholder="Email" required="Campo requerido">
                        <span class="fa fa-envelope-o form-control-feedback left" aria-hidden="true"></span>
                      </div>

                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <div class='input-group date' id='myDatepicker2'>
                            <input type='text' id="fecha_nac" name="fecha_nac" class="form-control" placeholder="Fecha Nacimiento (AAAA/MM/DD) Ejem: 1988/02/01" required="Campo requerido"/>
                            <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                      </div>
                      
                      <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <select class="form-control selectpicker" data-live-search="true" id="idoficina" name="idoficina" required="Campo requerido">
                            </select>
                      </div>

                      <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <select class="form-control selectpicker" data-live-search="true" id="iddepartamento" name="iddepartamento" required="Campo requerido">
                            </select>
                      </div>

                      <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <select class="form-control selectpicker" data-live-search="true" id="idcargo" name="idcargo" required="Campo requerido">
                                <option value="" selected disabled>Seleccione Cargo</option>
                            </select>
                      </div>
                           
                      <div class="clearfix"></div>
                      <div class="ln_solid"></div>

                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                          <button class="btn btn-primary" type="button" id="btnCancelar" onclick="cancelarform()">Cancelar</button>
                          <button class="btn btn-primary" type="reset" id="btnLimpiar" onclick="limpiar()">Limpiar</button>
                          <button class="btn btn-success" type="submit" id="btnGuardar">Agregar</button>
                        </div>
                      </div>
                    </form>
                  </div>

                  <div id="agradecer" class="x_content">
                    <div class="bs-example" data-example-id="simple-jumbotron">
                    <div class="jumbotron">
                      <h1>Gracias por registrarse</h1>
                    </div>
                  </div>
                    
                  </div>

                </div>
              </div>
              <!-- /form input mask -->
            </div>
          </div>
        </div>
        <!-- /page content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="../public/build/js/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../public//build/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../public/build/js/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../public/build/js/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="../public/build/js/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="../public/build/js/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../public/build/js/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../public/build/js/icheck.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../public/build/js/moment.min.js"></script>
    <script src="../public/build/js/daterangepicker.js"></script>
    <!-- bootstrap-select -->
    <script src="../public/build/js/bootstrap-select.min.js"></script>
    <!-- bootstrap-datetimepicker -->    
    <script src="../public/build/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Bootstrap Colorpicker -->
    <script src="../public/build/js/bootstrap-colorpicker.min.js"></script>
    <!-- Bootbox Alert -->
    <script src="../public/build/js/bootbox.min.js"></script>
    <!-- jQuery Tags Input -->
    <script src="../public/build/js/jquery.tagsinput.js"></script>
    <!-- jquery.inputmask -->
    <script src="../public/build/js/jquery.inputmask.bundle.min.js"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="../public/build/js/bootstrap-wysiwyg.min.js"></script>
    <script src="../public/build/js/jquery.hotkeys.js"></script>
    <script src="../public/build/js/prettify.js"></script>
    <!-- Switchery -->
    <script src="../public/build/js/switchery.min.js"></script>
    <!-- Select2 -->
    <script src="../public/build/js/select2.full.min.js"></script>
    <!-- Autosize -->
    <script src="../public/build/js/autosize.min.js"></script>
    <!-- jQuery autocomplete -->
    <script src="../public/build/js/jquery.autocomplete.min.js"></script>


    <!-- Custom Theme Scripts -->
    <script src="../public/build/js/custom.js"></script>
  </body>
</html>

<script>
    $('#myDatepicker2').datetimepicker({
        format: 'YYYY/MM/DD'
    });
</script>
<script type="text/javascript" src="scripts/regempleado.js"></script>