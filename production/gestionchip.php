<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
	header("Location:login.php");
} else {

	require 'header.php';

	if ($_SESSION['administrador'] == 1) {
?>
		<!-- page content -->
		<div class="right_col" role="main">
			<div class="">
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="x_title">
								<h2>Tarjetas SIM Robadas</h2>
								<ul class="nav navbar-right panel_toolbox">
									<button type="button" id="boton_regresar" onclick="mostrarform(false);" class="btn btn-primary">Regresar</button>
								</ul>
								<div class="clearfix"></div>
							</div>
							<div id="listadochips" class="x_content">

								<table id="tblchips" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Opciones</th>
											<th>Operador</th>
											<th>Numero</th>
											<th>Serial</th>
											<th>Disponibilidad</th>
											<th>Ultima Gesti&oacute;n</th>
											<th>Fecha Ultima Gesti&oacute;n</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>


							<div class="row" id="verGestion">
								<div class="col-md-12">
									<div class="x_panel">
										<div class="x_title">
											<h2>Gesti&oacute;n de SIM</h2>
											<div class="clearfix"></div>
										</div>

										<div class="x_content">

											<!-- start project-detail sidebar -->
											<div class="col-md-3 col-sm-3 col-xs-12">

												<section class="panel">

													<div class="x_title">
														<h2>Descripci&oacute;n</h2>
														<div class="clearfix"></div>
													</div>
													<div class="panel-body">

														<div class="project_detail">
															<p class="title">Operador</p>
															<p id="operador_sim"></p>
															<p class="title">N&uacute;mero</p>
															<p id="numero_sim"></p>
															<p class="title">Serial</p>
															<p id="serial_sim"></p>
															<p class="title">Disponibilidad</p>
															<p id="disponibilidad_sim"></p>
														</div>

														<br />

													</div>

												</section>

											</div>
											<!-- end project-detail sidebar -->

											<div class="col-md-9 col-sm-9 col-xs-12">
												<div>
													<h4>&nbsp;</h4>
													<!-- end of user messages -->
													<ul class="messages" id="listaGestion"> </ul>
													<!-- end of user messages -->
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

		<!-- Modal -->
		<div class="modal fade" id="modalGestion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span>
							<span class="sr-only">Close</span>
						</button>
						<h4 class="modal-title" id="">Realizar Gesti&oacute;n</h4>
					</div>
					<form id="formGestion" name="formGestion">
						<input type="hidden" name="idgestion" id="idgestion" value="">
						<input type="hidden" name="idchip" id="idchip" value="">
						<div class="modal-body">
							<div class="form-group">
								<label for="detalle">Estado de la gesti&oacute;n</label>
								<input type="text" class="form-control" id="detalle" name="detalle" style="text-transform: uppercase;" placeholder="Ingrese el estado de la gestión" required="required" maxlength="100">
							</div>
							<div class="form-group">
								<label for="descripcion">Descripci&oacute;n</label>
								<textarea id="descripcion" name="descripcion" class="form-control" style="text-transform: uppercase;" placeholder="Ingrese la descripción" required="required"></textarea>
							</div>
							<div class="form-check">
								<input type="checkbox" class="form-check-input" id="hablilitar" name="hablilitar">
								<label class="form-check-label" for="hablilitar">Habilitar SIM</label>
							</div>
							<div class="form-group hidden serie">
								<label class="form-check-label" for="nserie">N° de Serie del chip</label>
								<input type="text" name="nserie" id="nserie" class="form-control">
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							<button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
						</div>
					</form>
				</div>
			</div>
		</div>

	<?php
	} else {
		require 'nopermiso.php';
	}
	require 'footer.php';
	?>
	<script type="text/javascript" src="scripts/gestionchip.js"></script>
<?php
}
ob_end_flush();
?>