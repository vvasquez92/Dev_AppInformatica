<?php 
ob_start();
session_start();

if(!isset($_SESSION["nombre"])){
  header("Location:login.php");
}else{

require 'header.php';

if( $_SESSION['administrador']==1)
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
                    <h2>Asignaciones</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-tooltip="tooltip" title="Operaciones" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-cog"></i></a>
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
                  <div id="listadoasignacion" class="x_content">

                    <table id="tblasignacion" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Empleado</th>                    
                          <th>RUT</th>
                          <th>Movil</th>
                          <th>Imei</th>
                          <th>Numero</th>
                          <th>Fecha</th>
                          <th>Tipo de Asignaci&oacute;n</th>
                          <th>Anexo Contrato</th>
                          <th>Acta Entrega</th>
                          <th>Acta Recepcion</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>

                  <div id="formularioasignacion" class="x_content">
                    <br />

                    <div class="col-md-12 center-margin">
	                  <form class="form-horizontal form-label-left" id="formulario" name="formulario">
	                    <div class="col-md-6 col-sm-12 col-xs-12 form-group">
	                      <label>MOVIL</label>
	                      <input type="hidden" id="idasignacion" name="idasignacion" class="form-control">
	                      <select class="form-control selectpicker" data-live-search="true" id="idequipo" name="idequipo" required="required"></select>
	                    </div>
	                    <div class="col-md-6 col-sm-12 col-xs-12 form-group">
	                    	<label>EMPLEADO</label>
	                      	<select class="form-control selectpicker" data-live-search="true" id="idempleado" name="idempleado" required="required"></select>
                            </div>	                   
	                    <div class="col-md-6 col-sm-12 col-xs-12 form-group">
	                    	<label>TARJETA SIM</label>
	                      	<select class="form-control selectpicker" data-live-search="true" id="idchip" name="idchip" required="required"></select>
	                    </div>	            
	                    <div class="col-md-6 col-sm-12 col-xs-12 form-group">
	                    	<label>FECHA</label>
                                <div class='input-group date' id='myDatepicker2'>
                                    <input type='text' id="fecha" name="fecha" class="form-control" placeholder="Fecha Asignacion" />
                                    <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
	                    </div>
                              <div class="col-md-12 col-sm-12 col-xs-12 form-group">
	                      <label>TIPO DE ASIGNACION</label>	                      
                              <select class="form-control selectpicker"  id="tasignacion" name="tasignacion" required="required">
                                  <option value="" selected="selected" disabled="disabled"><--SELECCIONE UNA OPCION--></option>
                                  <option value="0">NUEVA ASIGNACION</option>
                                  <option value="1">REPOSICION</option>
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
}else{
  require 'nopermiso.php';
}
require 'footer.php';
?>
<script>
    $('#myDatepicker2').datetimepicker({
        format: 'DD-MM-YYYY'
    });
</script>
<script src="../public/build/js/jspdf.min.js"></script>
<script src="../public/build/js/jspdf.plugin.autotable.js"></script>
<script src="../public/build/js/jsPDFcenter.js"></script>


<script type="text/javascript" src="scripts/asignacion.js"></script>
<?php 
}
ob_end_flush();
?>