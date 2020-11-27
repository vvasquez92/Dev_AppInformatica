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
                    <h2>Usuarios</h2>
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
                  <div id="listadousuarios" class="x_content">

                    <table id="tblusuarios" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Opciones</th>
                          <th>Nombre</th>
                          <th>Apellido</th>
                          <th>Username</th>
                          <th>Email</th>
                          <th>Rol</th>                          
                          <th>Tipo documento</th>
                          <th>Nro documento</th>  
                          <th>Telefono</th> 
                          <th>Dirección</th>                                
                          <th>Estado</th>     
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>

                  <div id="formulariousuarios" class="x_content">
                    <br />

                    <div class="col-md-12 center-margin">
	                  <form class="form-horizontal form-label-left" id="formulario" name="formulario">
                      <input type="hidden" name="imagen_cam" id="imagen_cam" > 
                      <input id="imagenactual" name="imagenactual" type="hidden" >
                      <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                        <label>Nombre</label>
                          <input type="hidden" id="iduser" name="iduser" class="form-control">
                          <input type="text" class="form-control" name="nombre" id="nombre" required="">
                      </div>	 
                      <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                        <label>Apellido</label>
                          <input type="text" class="form-control" name="apellido" id="apellido" required="">
                      </div>
                      <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                        <label>Username</label>
                          <input type="text" class="form-control" name="username" id="username" required="">
                      </div>
                      <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                        <label>Password</label>
                          <input type="text" class="form-control" name="password" id="password" required="">
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                        <label>Email</label>
                          <input type="text" class="form-control" name="email" id="email" required="">
                      </div>  
                      <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                        <label>Direccion</label>
                          <input type="text" class="form-control" name="direccion" id="direccion" required="">
                      </div>
                      <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                        <label>Teléfono</label>
                          <input type="text" class="form-control" name="telefono" id="telefono" required="">
                      </div>    
                      <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                        <label>Fecha Nacimiento</label>
                          <input type="text" class="form-control" name="fecha_nac" id="fecha_nac" required="">
                      </div>                                            
                      <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                        <label>Tipo Documento</label>
                        <select class="form-control selectpicker" data-live-search="true" id="tipo_documento" name="tipo_documento" required="required">
                            <option value="" selected disabled>Tipo de Documento</option>
                            <option value="RUT">RUT</option>
                            <option value="P">Pasaporte</option>
                            <option value="O">Otro</option>
                        </select>
                      </div>    
                      <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                        <label>Numero Documento</label>
                          <input type="text" class="form-control" name="num_documento" id="num_documento" required="">
                      </div>   
                      <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                        <label>Rol</label>
                        <select class="form-control selectpicker" data-live-search="true" id="idrole" name="idrole" required="required">
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
<script type="text/javascript" src="scripts/usuario.js"></script>
<?php 
}
ob_end_flush();
?>