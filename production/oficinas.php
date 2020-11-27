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
                    <h2>Oficinas</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
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
                  <div id="listadooficinas" class="x_content">

                    <table id="tbloficinas" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Opciones</th>
                          <th>Oficina</th>    
                          <th>Dirección</th>   
                          <th>Comuna</th>   
                          <th>Provincia</th>  
                          <th>Región</th>                      
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>

                  <div id="formulariooficinas" class="x_content">
                    <br />

                    <div class="col-md-12 center-margin">
	                  <form class="form-horizontal form-label-left" id="formulario" name="formulario">
                      <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <label>Oficina</label>
                          <input type="hidden" id="idoficinas" name="idoficinas" class="form-control">
                          <input type="text" class="form-control" name="nombre" id="nombre" required="">
                      </div>	
                      <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <label>Dirección</label>
                          <input type="text" class="form-control" name="direccion" id="direccion" required="">
                      </div>    
                      <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <label>Región</label>
                        <select class="form-control selectpicker" data-live-search="true" id="idregiones" name="idregiones" required="required">
                                  <option value="" selected disabled>Region</option>
                                  </select>
                      </div>
                      <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <label>Provincia</label>
                        <select class="form-control selectpicker" data-live-search="true" id="idprovincias" name="idprovincias" required="required">
                                  <option value="" selected disabled>Provincia</option>
                                  </select>
                      </div>
                      <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <label>Comuna</label>
                        <select class="form-control selectpicker" data-live-search="true" id="idcomunas" name="idcomunas" required="required">
                                  <option value="" selected disabled>Comuna</option>
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
<script type="text/javascript" src="scripts/oficinas.js"></script>
<?php 
}
ob_end_flush();
?>