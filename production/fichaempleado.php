<?php 
ob_start();
session_start();

if(!isset($_SESSION["nombre"])){
  header("Location:login.php");
}else{

require 'header.php';

if( $_SESSION['administrador']==1 || $_SESSION['RRHH']==1)
{

 ?>
        
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>EMPLEADOS </h2>              
                    <div class="clearfix"></div>
                  </div>
                  <div id="" class="x_content">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                      <select class="form-control selectpicker" data-live-search="true" id="idempleado" name="idempleado" required="required"></select>                           
                      </div>              
                  </div>
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
                         <img src=""  alt="" id="imagen_empleado" width="300" height="300">
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
                             <dd  id="documento_empleado"></dd><br>                     
                            </dl> 
                        </div>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="border:0px solid #e5e5e5;"> 
                          <h3 class="prod_title">&nbsp;</h3>
                          <dl>
                              <dt>Tel&eacute;fono M&oacute;vil</dt>
                              <dd id="movil_empleado"></dd><br>
                              <dt>Tel&eacute;fono Residencial</dt>
                              <dd id="residencial_empleado"></dd><br>
                              <dt>Correo Electr&oacute;nico</dt>
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
                    <dt>Correo Corporativo</dt>
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
                        <h4 class="panel-title"><i class="fa fa-car"></i>&nbsp;Vehiculo Asignado</h4>
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

<?php 
}else{
  require 'nopermiso.php';
}
require 'footer.php';
?>
<script type="text/javascript" src="scripts/fichaempleado.js"></script>
<?php 
}
ob_end_flush();
?>