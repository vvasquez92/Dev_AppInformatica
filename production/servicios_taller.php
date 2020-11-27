<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location:login.php");
} else {

    require 'header.php';

    if ($_SESSION['administrador'] == 1 || $_SESSION['vehiculos'] == 1 || $_SESSION['Mecanico'] == 1) {

?>
        <div class="right_col" role="main">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="col-md-5 col-sm-5 col-xs-5">
                                <h2>SERVICIOS TALLER</h2>
                            </div>
                            <div id="filtros" class="dt-buttons btn-group">
                                <p class="btn btn-xs buttons-copy buttons-html5">FILTROS</p>
                                <a class="btn btn-xs buttons-copy buttons-html5" style="color: black;" onclick="listarServicios('1,2,3,4,5');">Todos</a>
                                <a id="fPend" name="fPend" class="btn btn-xs buttons-copy buttons-html5" style="color:white; background-color: #314A65;" onclick="listarServicios('1');">SOLICITUDES</a>
                                <a id="fProc" name="fProc" class="btn btn-xs buttons-copy buttons-html5" style="color:white; background-color: #C10000;" onclick="listarServicios('2');">PENDIENTES</a>
                                <a id="fFin" name="fFin" class="btn btn-xs buttons-copy buttons-html5" style="color:white; background-color: #D0C700;" onclick="listarServicios('3');">EN TALLER</a>
                                <a id="fSol" name="fSol" class="btn btn-xs buttons-copy buttons-html5" style="color:white; background-color: #149100;" onclick="listarServicios('4');">FINALIZADOS</a>
                                <a id="fSol" name="fSol" class="btn btn-xs buttons-copy buttons-html5" style="color:white; background-color: #989898;" onclick="listarServicios('5');">ENTREGADOS</a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div id='calendario'></div>
                        </div>
                        <div class="x_content">
                            <div id='recepcion'>
                                <form id="recepcionForm" class="form-horizontal calender" role="form" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <div class="col-sm-6 col-xs-12">
                                            <label class="control-label">VEHÍCULO</label>
                                            <input type="text" class="form-control" id="patenteRecibe" name="patenteRecibe" readonly>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <label class="control-label">RUT</label>
                                            <input type="text" class="form-control" id="rutRecibe" name="rutRecibe" placeholder="INGRESE SU RUT" data-inputmask="'mask' : '99.999.999-*'" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6 col-xs-12">
                                            <label class="control-label">NOMBRE</label>
                                            <input type="text" class="form-control" id="nombreRecibe" name="nombreRecibe" placeholder="INGRESE SU NOMBRE" required="required" style="text-transform:uppercase;" maxlength="100">
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <label class="control-label">CORREO</label>
                                            <input type="text" class="form-control" id="correoRecibe" name="correoRecibe" placeholder="INGRESE SU CORREO" required="required" style="text-transform:uppercase;" maxlength="100">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <label for="firma">Firma <span class="required">*</span></label>
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
                                            <button class="btn btn-primary" type="button" id="btnCancelar" onclick="cancelaEntrega()">CANCELAR</button>
                                            <button class="btn btn-success" type="submit" id="btnGuardar">ENVIAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="ModalSolicServ" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="SolicServform" class="form-horizontal calender" role="form" enctype="multipart/form-data" autocomplete="off">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">SOLICITUD DE SERVICIO</h4>
                        </div>
                        <div class="modal-body">
                            <div id="testmodal" style="padding: 5px 10px;">
                                <div class="form-group">
                                    <div class="col-sm-12 col-xs-12">
                                        <input type="hidden" name="id_servicio" id="id_servicio">
                                        <label class="control-label">TIPO DE SERVICIO</label>
                                        <textarea class="form-control" style="height:35px; width:100%; text-transform: uppercase; resize:none;" id="tipServicio" name="tipServicio" placeholder="TIPO DE SERVICIO" readonly></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12 col-xs-12">
                                        <label class="control-label">COMENTARIOS</label>
                                        <textarea class="form-control" style="height:70px; width:100%; text-transform: uppercase; resize:none;" id="comentarioServ" name="comentarioServ" placeholder="COMENTARIOS" readonly></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div id="div_desde">
                                        <div class="col-sm-6 col-xs-12">
                                            <label>FECHA INICIO</label>
                                            <input type='date' id="fhIniServ" name="fhIniServ" class="form-control" required="Campo requerido" onpaste="return false"/>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <label>HORA INICIO</label>
                                            <div class='input-group date' id='myDatepicker_hrDesde'>
                                                <input type='text' name="hrDesde" id="hrDesde" class="form-control" placeholder="INICIO" required="Campo requerido" onpaste="return false"/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div id="div_hasta">
                                        <div class="col-sm-6 col-xs-12">
                                            <label>FECHA FIN</label>
                                            <input type='date' id="fhFinServ" name="fhFinServ" class="form-control" required="Campo requerido" onpaste="return false"/>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <label>HORA FIN</label>
                                            <div class='input-group date' id='myDatepicker_hrHasta'>
                                                <input type='text' name="hrHasta" id="hrHasta" class="form-control" placeholder="FIN" required="Campo requerido" onpaste="return false"/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">CANCELAR</button>
                            <button type="submit" id="enviar" class="btn btn-primary">GUARDAR</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <div id="ModalServicio" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="Servicioform" class="form-horizontal calender" role="form" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">SERVICIO</h4>
                        </div>
                        <div class="modal-body">
                            <div id="testmodal" style="padding: 5px 10px;">
                                <div class="form-group">
                                    <div class="col-sm-12 col-xs-12">
                                        <label class="control-label">VEHÍCULO</label>
                                        <input type="text" class="form-control" name="lblDatosVeh" id="lblDatosVeh" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6 col-xs-12">
                                        <input type="hidden" name="idServicio" id="idServicio">
                                        <label class="control-label">TIPO DE SERVICIO</label>
                                        <input type="text" class="form-control" name="stipServicio" id="stipServicio" readonly>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <label class="control-label">KILOMETRAJE ACTUAL</label>
                                        <input type="text" class="form-control" name="sKmsActual" id="sKmsActual" required="required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6 col-xs-12">
                                        <label class="control-label">FECHA HORA INICIO</label>
                                        <input type="datetime-local" class="form-control" name="sFhIniServ" id="sFhIniServ" required="required">
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <label class="control-label">FECHA HORA FIN</label>
                                        <input type="datetime-local" class="form-control" name="sFhFinserv" id="sFhFinserv" required="required">
                                    </div>
                                </div>                                
                                <div class="form-group">
                                    <div class="col-sm-6 col-xs-12" id="divRep">
                                        <label class="control-label">REPUESTO</label>
                                        <select class="form-control selectpicker" data-live-search="true" id="sRep" name="sRep">
                                        </select>
                                    </div>
                                    <div class="col-sm-5 col-xs-10" id="divCant">
                                        <label class="control-label">CANTIDAD</label>
                                        <input type="hidden" name="cantMaxima" id="cantMaxima">
                                        <input type="number" class="form-control" name="sCantRep" id="sCantRep" min="0" max="99999999999" onKeyPress="if(this.value.length==11) return false;">
                                    </div>
                                    <div class="col-sm-1 col-xs-2">
                                        <button type="button" id="agregarRep" class="btn btn-success antoclose" onclick="agregarRepuestos();" style="margin-top:27px;"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="form-group" id="divTab">
                                    <div class="col-sm-12 col-xs-12">
                                        <table id="tblrepuestos" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>REPUESTO</th>
                                                    <th>CANTIDAD</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">CERRAR</button>
                            <button type="button" id="reprogramarServ" onclick="reprogServicio();" class="btn btn-warning">REPROGRAMAR</button>
                            <button type="button" id="iniciarServ" onclick="iniciarServicio();" class="btn btn-primary">INICIAR</button>
                            <button type="button" id="finalizarServ" onclick="finalizarServicio();" class="btn btn-primary">FINALIZAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="ModalReprog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="Reprogform" class="form-horizontal calender" role="form" enctype="multipart/form-data" autocomplete="off">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">REPROGRAMAR SERVICIO</h4>
                        </div>
                        <div class="modal-body">
                            <div id="testmodal" style="padding: 5px 10px;">
                                <div class="form-group">
                                    <div class="col-sm-12 col-xs-12">
                                        <input type="hidden" name="id_servicio" id="id_servicio">
                                        <label class="control-label">INGRESE MOTIVO</label>
                                        <textarea class="form-control" style="height:70px; width:100%; text-transform: uppercase; resize:none;" id="motivoReprog" name="motivoReprog" placeholder="INGRESE MOTIVO PARA LA REPROGRAMACIÓN" required="required"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6 col-xs-12">
                                        <label>FECHA INICIO</label>
                                        <input type='date' id="fhIniServReprog" name="fhIniServReprog" class="form-control" required="Campo requerido" />
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <label>HORA INICIO</label>
                                        <div class='input-group date' id='myDatepicker_hrDesdeReprog'>
                                            <input type='text' name="hrDesdeReprog" id="hrDesdeReprog" class="form-control" placeholder="INICIO" required="Campo requerido" onpaste="return false"/>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <label>FECHA FIN</label>
                                        <input type='date' id="fhFinServReprog" name="fhFinServReprog" class="form-control" required="Campo requerido" />
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <label>HORA FIN</label>
                                        <div class='input-group date' id='myDatepicker_hrHastaReprog'>
                                            <input type='text' name="hrHastaReprog" id="hrHastaReprog" class="form-control" placeholder="FIN" required="Campo requerido" onpaste="return false"/>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">CANCELAR</button>
                            <button type="submit" id="enviar" class="btn btn-primary">MODIFICAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="ModalEntregado" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="Entregadoform" class="form-horizontal calender" role="form" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">SERVICIO FINALIZADO</h4>
                        </div>
                        <div class="modal-body">
                            <div id="testmodal" style="padding: 5px 10px;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="control-label">VEHÍCULO</label>
                                        <input type="text" class="form-control" name="e_lblDatosVeh" id="e_lblDatosVeh" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <input type="hidden" name="e_idServicio" id="e_idServicio">
                                        <label class="control-label">TIPO DE SERVICIO</label>
                                        <input type="text" class="form-control" name="e_stipServicio" id="e_stipServicio" readonly>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label">KILOMETRAJE ACTUAL</label>
                                        <input type="text" class="form-control" name="e_sKmsActual" id="e_sKmsActual" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label class="control-label">FECHA HORA INICIO</label>
                                        <input type="datetime-local" class="form-control" name="e_sFhIniServ" id="e_sFhIniServ" readonly>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="control-label">FECHA HORA FIN</label>
                                        <input type="datetime-local" class="form-control" name="e_sFhFinserv" id="e_sFhFinserv" readonly>
                                    </div>
                                </div>  
                                <div class="form-group" id="divTab">
                                    <div class="col-sm-12">
                                        <table id="tblrepuestosEntregados" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>REPUESTO</th>
                                                    <th>CANTIDAD</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">CERRAR</button>
                            <button type="button" id="e_descargarPDF" onclick="descargarPDF();" class="btn btn-primary">DESCARGAR PDF</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="btnSolicServ" data-toggle="modal" data-target="#ModalSolicServ"></div>
        <div id="btnServicio" data-toggle="modal" data-target="#ModalServicio"></div>
        <div id="btnEntrega" data-toggle="modal" data-target="#ModalEntrega"></div>
        <div id="btnReprogServ" data-toggle="modal" data-target="#ModalReprog"></div>
        <div id="btnEntregado" data-toggle="modal" data-target="#ModalEntregado"></div>

        <?php
        require 'footer.php';
        ?>

        <script type="text/javascript" src="../public/build/js/instascan.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
        <script type="text/javascript" src="scripts/servicios_taller.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
        <script src="../public/build/js/jspdf.plugin.autotable.js"></script>
        <script src="../public/build/js/jsPDFcenter.js"></script>



<?php
    }
}
ob_end_flush();
?>