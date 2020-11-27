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
                    <h2>Dispositivos</h2>
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
                  <div id="listadodispositivos" class="x_content">

                    <table id="tbldispositivos" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th>TIPO</th>                         
                          <th>MODELO</th>
                          <th>SERIAL</th>
                          <th>MAC LAN</th>                          
                          <th>DISPONIBLE</th>
                          <th>ESTADO</th>
                          <th>CONDICION</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>

                  <div id="formulariodispositivos" class="x_content">
                    <br />

                    <div class="col-md-12 center-margin">
	                  <form class="form-horizontal form-label-left" id="formulario" name="formulario" enctype="multipart/form-data">
                              <input type="hidden" id="factura_actual" name="factura_actual">
	                    <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                <label>TIPO</label><span class="required">*</span>
                                <input type="hidden" id="iddispositivo" name="iddispositivo" class="form-control">
                                <select class="form-control selectpicker" data-live-search="true" id="tdispositivo" name="tdispositivo" required="required"></select>
	                    </div>
                            <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                <label>MARCA</label><span class="required">*</span>
                                <input type="text" class="form-control" name="marca" id="marca" required="required" maxlength="100">
	                    </div>
                            <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                <label>MODELO</label><span class="required">*</span>
                                <input type="text" class="form-control" name="modelo" id="modelo" required="required" maxlength="100">
	                    </div>
                              <div class="col-md-3 col-sm-12 col-xs-12 form-group">
	                      <label>ESTADO</label><span class="required">*</span>
	                      <select class="form-control selectpicker" data-live-search="true" id="estado" name="estado" required="required">
	                      		<option value="" selected disabled>SELECIONE ESTADO</option>
	                      		<option value="1">NUEVO</option>
                                        <option value="0">USADO</option>
                                </select>
	                    </div>
                              
                            <div class="col-md-3 col-sm-12 col-xs-12 form-group">
	                    	<label>SERIAL</label><span class="required">*</span>
	                      	<input type="text" class="form-control" name="serial" id="serial" required="required" maxlength="100">
                            </div>
	                    <div class="col-md-3 col-sm-12 col-xs-12 form-group">
	                    	<label>MAC LAN</label>
	                      	<input type="text" class="form-control" name="maclan" id="maclan" maxlength="100">
                            </div>	                   
	                    <div class="col-md-3 col-sm-12 col-xs-12 form-group">
	                    	<label>MAC WIFI</label>
	                      	<input type="text" class="form-control" name="macwifi" id="macwifi" maxlength="100">
                            </div>
                              <div class="col-md-3 col-sm-12 col-xs-12 form-group">
	                    	<label>DIRECCION IP ASIGNADA</label>
                                <input type="text" class="form-control" name="ip" id="ip" data-inputmask="'mask' : '999.999.999.999'">
                            </div>
                                <div class="col-md-5 col-sm-12 col-xs-12 form-group">
	                    	<label>FACTURA</label>
                                <input type="file" class="form-control" name="factura" id="factura" accept="application/pdf">
                                <small id="emailHelp" class="form-text text-muted">Solo Archivos con Extensi&oacute;n PDF</small>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12 form-group">
                                   <label>&nbsp;</label><br>
                                   <div id="previa_factura"></div>
                            </div>
	                    <div class="col-md-6 col-sm-12 col-xs-12 form-group has-feedback">
	                    	<label>FECHA DE VENCIMIENTO DE LA GARANTIA</label>
                                 <input type="text" class="form-control has-feedback-left form_datetime" id="fvencimiento_garantia" name="fvencimiento_garantia" placeholder="Fecha de vencimiento de la garantia" >
                                 <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>                          
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <label for="observaciones">ESPECIFICACIONES</label>
                                <textarea type="text" id="observaciones" name="observaciones" class="form-control"></textarea>
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
    $('.form_datetime').datetimepicker({
        format: 'YYYY-MM-DD'
    });
</script>
<script type="text/javascript" src="scripts/dispositivo.js"></script>
<?php 
}
ob_end_flush();
?>