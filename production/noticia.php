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
                    <h2>NOTICIAS</h2>
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
                  <div id="listadonoticias" class="x_content">
                    <table id="tblnoticias" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>OPCIONES</th>
                          <th>TITULO</th>
                          <th>ESTATUS</th>
                           <th>VISIBLE EN EL HOME</th>
                          <th>AUTOR</th>
                          <th>DEPARTAMENTO</th>
                          <th>FECHA DE CREACION</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>

                  <div id="formularionoticias" class="x_content">
                    <br />
                    <form id="formulario" name="formulario">
                        <input type="hidden" name="idnoticia" id="idnoticia" >
                    <div class="form-group">
                        <label for="titulo">T&iacute;tulo</label>
                        <input type="text" class="form-control" id="titulo" name="titulo"  placeholder="Ingrese el título" maxlength="255" required="required">                      
                    </div>
                    <div class="form-group">
                      <label for="contenido">Cuerpo</label>
                      <textarea id="contenido" name="contenido" rows="3" class="form-control" placeholder="Ingrese el contenido" required="required"></textarea>
                    </div>
                      <div class="form-group">
                        <label for="imagen">Imagenes</label>
                        <input type="file" class="form-control-file" id="galleryImgs" name="imagenes[]" multiple="multiple" accept="image/jpg, image/jpeg, image/png">
                      </div>
                        <div class="form-group" id="div_imagenes"></div>
                      <div class="form-group">
                        <label for="iddepartamento">Departamento</label>
                        <select class="form-control" id="iddepartamento" name="iddepartamento" data-live-search="true" required="required">
                        </select>
                      </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">                       
                          <button class="btn btn-primary" type="button" id="btnCancelar" onclick="cancelarform()">Cancelar</button>
                          <button class="btn btn-primary" type="reset" id="btnLimpiar" onclick="limpiar()">Limpiar</button>
                          <button class="btn btn-success" type="submit" id="btnGuardar">Agregar</button>                      
                        </div>
                  </form>
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

<script type="text/javascript" src="scripts/noticia.js"></script>
<?php 
}
ob_end_flush();
?>