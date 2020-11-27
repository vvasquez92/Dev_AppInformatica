var tabla;


//funcion que se ejecuta iniciando
function init() {
  mostarform(false);
  listar();

  $('[data-toggle="tooltip"]').tooltip();

  $("#formulario").on("submit", function (e) {
    guardaryeditar(e);
  })

  $.post("../ajax/equipo.php?op=selectequipo", function (r) {
    $("#idequipo").html(r);
    $("#idequipo").selectpicker('refresh');
  });

  $.post("../ajax/empleado.php?op=selectempleado", function (r) {
    $("#idempleado").html(r);
    $("#idempleado").selectpicker('refresh');
  });

  $.post("../ajax/chip.php?op=selectChip", function (r) {
    $("#idchip").html(r);
    $("#idchip").selectpicker('refresh');
  });

}


// Otras funciones
function limpiar() {

  $("#idasignacion").val("");
  $("#idequipo").val("");
  $("#idequipo").selectpicker('refresh');
  $("#idempleado").val("");
  $("#idempleado").selectpicker('refresh');
  $("#tasignacion").val("");
  $("#tasignacion").selectpicker('refresh');
  $("#idchip").val("");
  $("#idchip").selectpicker('refresh');
  $("#fecha").val("");
}

function mostarform(flag) {

  limpiar();
  if (flag) {
    $("#listadoasignacion").hide();
    $("#formularioasignacion").show();
    $("#op_agregar").hide();
    $("#op_listar").show();
    $("#btnGuardar").prop("disabled", false);

  } else {
    $("#listadoasignacion").show();
    $("#formularioasignacion").hide();
    $("#op_agregar").show();
    $("#op_listar").hide();
  }

}

function cancelarform() {
  limpiar();
  mostarform(false);
}

function listar() {
  tabla = $('#tblasignacion').dataTable({
    "aProcessing": true,
    "aServerSide": true,
    dom: 'Bfrtip',
    buttons: [
      'copyHtml5',
      'print',
      'excelHtml5',
      'csvHtml5',
      'pdf'
    ],
    "ajax": {
      url: '../ajax/asignacion.php?op=listar',
      type: "get",
      dataType: "json",
      error: function (e) {
        console.log(e.responseText);
      }
    },
    "language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
		  },
    "bDestroy": true,
    "iDisplayLength": 10, //Paginacion 10 items
    "order": [[1, "desc"]] //Ordenar en base a la columna 0 descendente
  }).DataTable();
}

function guardaryeditar(e) {
  e.preventDefault();
  $("#btnGuardar").prop("disabled", true);
  var formData = new FormData($("#formulario")[0]);
  $.ajax({
    url: '../ajax/asignacion.php?op=guardaryeditar',
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      bootbox.alert(datos);
      mostarform(false);
      tabla.ajax.reload();
    }
  });
  limpiar();
}

function mostar(idasignacion) {
  $.post("../ajax/asignacion.php?op=mostar", { idasignacion: idasignacion }, function (data, status) {
    data = JSON.parse(data);
    mostarform(true);

    $("#idasignacion").val(data.idasignacion);
    $("#idequipo").val(data.idequipo);
    $("#idequipo").selectpicker('refresh');
    $("#idempleado").val(data.idempleado);
    $("#idempleado").selectpicker('refresh');
    $("#idchip").val(data.idchip);
    $("#idchip").selectpicker('refresh');

  })
}

function desactivar(idasignacion, idequipo, idchip) {

  bootbox.confirm("Esta seguro que quiere inhabilitar la asignacion?", function (result) {
    if (result) {
      bootbox.prompt({
        title: "Seleccione el motivo de la devolución",
        inputType: 'select',
        inputOptions: [
          {
            text: 'Inhabilitar por Devolución',
            value: '0',
          },
          {
            text: 'Inhabilitar por teléfono Descompuesto',
            value: '2',
          },
          {
            text: 'Inhabilitar por Robo',
            value: '3',
          }
        ],
        callback: function (result) {
          if (result !== null) {
            $.post("../ajax/asignacion.php?op=desactivar", { idasignacion: idasignacion, idequipo: idequipo, idchip: idchip, condicion: result }, function (e) {
              bootbox.alert(e);
              tabla.ajax.reload();
            })
          }
        }
      });
    }
  });
}


function checkcontrato(idasignacion) {

  bootbox.confirm("Esta seguro que quiere marcar el contrato como entregado?", function (result) {
    if (result) {
      $.post("../ajax/asignacion.php?op=checkcontrato", { idasignacion: idasignacion }, function (e) {
        bootbox.alert(e);
        tabla.ajax.reload();
      })
    }
  })
}

function checkacta(idasignacion) {

  bootbox.confirm("Esta seguro que quiere marcar el acta de entrega como entregada?", function (result) {
    if (result) {
      $.post("../ajax/asignacion.php?op=checkacta", { idasignacion: idasignacion }, function (e) {
        bootbox.alert(e);
        tabla.ajax.reload();
      })
    }
  })
}

function checkdev(idasignacion) {

  bootbox.confirm("Esta seguro que quiere marcar el acta de devolucion como entregada?", function (result) {
    if (result) {
      $.post("../ajax/asignacion.php?op=checkdevolucion", { idasignacion: idasignacion }, function (e) {
        bootbox.alert(e);
        tabla.ajax.reload();
      })
    }
  })
}

function contrato(idasignacion) {

  $.post("../ajax/asignacion.php?op=pdfcontrato", { idasignacion: idasignacion }, function (data, status) {
    data = JSON.parse(data);
    tabla.ajax.reload();

    if (data.estado == 1) {
      var condicion = 'Nuevo';
    } else {
      var condicion = 'Usado';
    }

    var compañiaJSON = {
      nombre: 'Fabrimetal SA',
      rut: '85.233.500-9'
    };

    var Mes = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

    var ParrafoJSON = {
      Inicio: 'En Santiago de Chile, a ' + data.dia + ' ' + Mes[data.mes - 1] + ' de ' + data.año + ', entre la sociedad FABRIMETAL S.A., RUT N° 85.233.500-9, debidamente representada por don Alfredo Schaub Rapp, Cédula Nacional de Identidad N° 10.330.748-1, ambos domiciliados para estos efectos en Volcán Lascar N° 818, Parque Industrial Lo Boza, comuna de Pudahuel, Santiago, en adelante  indistintamente "El Empleador" o “La Empresa”, por una parte; y por la otra, don ' + data.nombre + ' ' + data.apellido + ' cédula de identidad número ' + data.num_documento + ', ' + data.direccion + ' ' + data.comuna + ' , en adelante, "El Trabajador", se ha convenido siguiente  anexo al contrato individual de trabajo:',
      Antecedentes: 'En la actualidad el Trabajador y la Empresa ,mantienen vigente un contrato de trabajo en virtud del  cual el primero se desempeña en calidad de ' + data.cargo + '.',
      Objeto1: '2.1 Con el objeto de desempeñar adecuadamente los servicios prestados para el Empleador, este permitirá el uso exclusivo de un TELÉFONO MÓVIL, con las características que se detallan a continuación y que estará bajo su responsabilidad durante el período que dure su contrato de trabajo:',
      Objeto2: '2.2 El terminal telefónico móvil que se entrega al Trabajador es una herramienta de trabajo, destinado exclusivamente a finalidades vinculadas a la prestación de los servicios convenidos en el contrato aludido en la cláusula primera de este instrumento.  En consecuencia se prohíbe la destinación de dicho bien a fines diversos a los convenidos o su utilización por parte de otras personas o dependientes del Trabajador para fines personales.',
      Obligaciones1: '3.1 El Trabajador será responsable del uso del teléfono móvil  y en consecuencia, responderá de los daños y perjuicios causados a la propiedad del Empleador. Sólo el Trabajador podrá utilizar el teléfono, no estando permitido su préstamo o entrega a terceros.',
      Obligaciones2: '3.2 Durante su jornada laboral el Trabajador deberá llevar el teléfono móvil consigo en todo momento durante la jornada laboral, mantenerlo  siempre encendido y deberá responder las llamadas que se le hagan, ya sea desde la empresa o por parte de clientes o de potenciales clientes. Deberá, asimismo, cargar cada día el dispositivo de manera de no quedar sin carga durante su jornada y tomar todos los resguardos que buenamente pueda para mantener el equipo en correcto funcionamiento durante ella.',
      Obligaciones3: '3.3 El Trabajador dispondrá de una cantidad de 150 minutos para realizar llamadas a todas las compañías móviles y dispositivos de red fija, en ambos casos dentro del territorio nacional. Estos minutos se otorgan para realizar llamadas a clientes. Adicionalmente, el trabajador dispone de minutos ilimitados para comunicarse con otros móviles de pertenencia de Fabrimetal S.A.',
      Obligaciones4: '3.4 El trabajador dispondrá de acceso a Internet móvil con el objeto de utilizar el mail corporativo y las aplicaciones web de la empresa, tales como el sistema de guía de servicio y otros que en el futuro se implementen.',
      Obligaciones5: '3.5 Cada móvil viene programado con las siguientes aplicaciones: Gmail (Correo Corporativo), Excel, Word, Mensajes, Cámara fotográfica, Calculadora, Google Drive, Google Chrome, Google Maps, Adobe Acrobat Reader, Google Keep, Whatsapp, Waze, Telegram y GPS permanentemente activo.\nEl Trabajador no podrá instalar aplicaciones distintas a las descritas. De ser necesario acceder y/o instalar aplicaciones que no están entre las detalladas, el Trabajador deberá solicitar a su jefe directo, vía correo electrónico, la autorización pertinente. Una vez que tal autorización sea otorgada a través de correo electrónico, el departamento de Sistemas y Servicios desbloqueará la aplicación necesitada. Se advierte específicamente que el Trabajador no debe tratar de desinstalar las aplicaciones asignadas ni intentar apagar el GPS. No debe modificar las políticas de pertenencia del móvil.\nLas Partes declaran que la instalación de programas y/o aplicaciones móviles en el equipo entregado, o la eliminación de instalación de aquellas enumeradas en el presente numeral, en ambos casos sin la autorización respectiva, se entenderán como una falta grave al deber de cuidado de esta herramienta de trabajo, lo que llevará aparejada las sanciones que correspondan de conformidad a la ley y a la normativa interna de la Empresa.',
      Obligaciones6: '3.6 El Trabajador declara saber y entender que Fabrimetal S.A., en su calidad de propietaria del equipo móvil, mantendrá en todo momento el control de los dispositivos en cuanto su ubicación, programas permitidos y control de llamadas. Se prohíbe estrictamente al trabajador usar la Sim Card de Fabrimetal asignada a él, en un teléfono distinto al entregado en este acto.',
      Obligaciones7: '3.7 El Trabajador deberá considerar el dispositivo telefónico siempre como una valiosa herramienta de trabajo y por tanto deberá prestar el debido cuidado en su uso y manejo. En caso de pérdida, daño o robo el Trabajador se obliga a informar de manera inmediata a su superior directo y al jefe del Departamento de Sistemas y Servicios, de la situación sufrida.',
      Obligaciones8: '3.8 No se puede realizar llamadas internacionales, descargar juegos, videos y música. Se prohíbe al Trabajador alterar, modificar y/o intervenir el dispositivo de cualquier manera.',
      Daños: 'El Trabajador asume, por el presente instrumento, la responsabilidad por cualquier daño, pérdida o destrucción que se produzca en el bien singularizado en la cláusula segunda de este instrumento, siempre que dicho daño, pérdida o destrucción provenga de un hecho imputable al Trabajador o falta de cuidado en su conservación.\nSe establece que el dispositivo tiene un valor para estos efectos de $' + data.precio + '.- (IVA incluido).',
      Restitucion: 'El Trabajador deberá presentar ante el departamento de Sistemas y Servicios el dispositivo entregado, cada vez que éste le sea solicitado formalmente vía mail, con copia a su jefatura.\nEn caso que el trabajador renuncie a su cargo y/o sea desvinculado de la empresa, éste deberá hacer entrega del dispositivo y sus accesorios, al Departamento de Sistemas y Servicios, que emitirá un documento de recepción consignando su recepción conforme o no. Esta recepción considerará el estado conforme tomando en cuenta el tiempo que el dispositivo estuvo en funcionamiento.\nEn todo caso, la Empresa tendrá el derecho a solicitar la restitución del equipo entregado en cualquier tiempo y sin expresión de causa. En tal caso, la entrega del equipo deberá realizarse en cuanto sea posible, y en todo caso, en un plazo no mayor a 24 horas desde su solicitud.',
      Final: 'El presente anexo se firma en dos ejemplares de idéntico tenor, quedando uno en poder de cada parte.'
    };

    var company_logo = {
      srcf: 'data:image/jpeg;base64,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',
      src: 'data:image/jpeg;base64,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',
      w: 200,
      h: 40,
      wf: 127
    };

    var fontSizes = {
      HeadTitleFontSize: 18,
      Head2TitleFontSize: 16,
      TitleFontSize: 14,
      SubTitleFontSize: 12,
      NormalFontSize: 10,
      SmallFontSize: 8
    };

    var lineSpacing = {
      NormalSpacing: 12,
      DobleSpacing: 24,
      LineSpacing: 10,
      LetterSpace: 6
    };


    var doc = new jsPDF('p', 'pt', 'letter');

    var rightStartCol1 = 400;
    var rightStartCol2 = 480;


    var InitialstartX = 40;
    var startX = 40;
    var InitialstartY = 50;
    var startY = 0;
    var lines = 0;
    var space = 0;

    doc.addImage(company_logo.src, 'PNG', startX, startY += 50, company_logo.w, company_logo.h);

    doc.setFontSize(fontSizes.NormalFontSize);

    doc.setFontType('bold');
    doc.text(startX, startY += 15 + company_logo.h, "Cod Documento: ");
    doc.setFontType('normal');
    doc.text(startX + 85, startY, "FF-SS-AN-" + data.idasignacion);
    doc.setFontType('bold');
    doc.setFontSize(fontSizes.TitleFontSize);
    doc.myText("ANEXO DE CONTRATO DE TRABAJO POR ENTREGA Y USO DE TELÉFONO", { align: "center" }, startX, startY += lineSpacing.DobleSpacing);

    doc.setFontSize(fontSizes.NormalFontSize);
    doc.setFontType('normal');
    lines = doc.splitTextToSize(ParrafoJSON.Inicio, 540);
    doc.text(startX, startY += lineSpacing.DobleSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    doc.setFontType('bold');
    doc.text(startX, startY += space + lineSpacing.DobleSpacing, "Primero: Antecedentes.");
    doc.setFontType('normal');
    lines = doc.splitTextToSize(ParrafoJSON.Antecedentes, 540);
    doc.text(startX, startY += lineSpacing.NormalSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    doc.setFontType('bold');
    doc.text(startX, startY += space + lineSpacing.DobleSpacing, "Segundo: Objeto.");
    doc.setFontType('normal');
    lines = doc.splitTextToSize(ParrafoJSON.Objeto1, 540);
    doc.text(startX, startY += lineSpacing.NormalSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    doc.setFontType('bold');
    doc.text(startX, startY += space + lineSpacing.DobleSpacing, "Tipo: ");
    doc.setFontType('normal');
    doc.text(startX + 25, startY, data.tipo);

    doc.setFontType('bold');
    doc.text(startX + 150, startY, "Marca: ");
    doc.setFontType('normal');
    doc.text(startX + 150 + 35, startY, data.marca);

    doc.setFontType('bold');
    doc.text(startX + 300, startY, "Modelo: ");
    doc.setFontType('normal');
    doc.text(startX + 300 + 40, startY, data.equipo);

    doc.setFontType('bold');
    doc.text(startX, startY += lineSpacing.DobleSpacing, "Color: ");
    doc.setFontType('normal');
    doc.text(startX + 35, startY, data.color);
    doc.setFontType('bold');
    doc.text(startX + 150, startY, "IMEI: ");
    doc.setFontType('normal');
    doc.text(startX + 150 + 30, startY, data.imei);
    doc.setFontType('bold');
    doc.text(startX + 300, startY, "Condición: ");
    doc.setFontType('normal');
    doc.text(startX + 300 + 55, startY, condicion);

    doc.setFontType('bold');
    doc.text(startX, startY += lineSpacing.DobleSpacing, "Operador: ");
    doc.setFontType('normal');
    doc.text(startX + 55, startY, data.operador);
    doc.setFontType('bold');
    doc.text(startX + 150, startY, "Número: ");
    doc.setFontType('normal');
    doc.text(startX + 150 + 45, startY, data.numero);
    doc.setFontType('bold');
    doc.text(startX + 300, startY, "Serial SIM: ");
    doc.setFontType('normal');
    doc.text(startX + 300 + 55, startY, data.serial);

    doc.setFontType('bold');
    doc.text(startX, startY += lineSpacing.DobleSpacing, "Accesorios: ");
    doc.setFontType('normal');
    doc.text(startX + 60, startY, "Cable USB, cargador, auriculares");

    doc.setFontType('normal');
    lines = doc.splitTextToSize(ParrafoJSON.Objeto2, 540);
    doc.text(startX, startY += lineSpacing.DobleSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    doc.setFontType('bold');
    doc.text(startX, startY += space + lineSpacing.DobleSpacing, "Tercero: Obligaciones y responsabilidades.");
    doc.setFontType('normal');
    lines = doc.splitTextToSize(ParrafoJSON.Obligaciones1, 540);
    doc.text(startX, startY += lineSpacing.NormalSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    lines = doc.splitTextToSize(ParrafoJSON.Obligaciones2, 540);
    doc.text(startX, startY += space + lineSpacing.NormalSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    lines = doc.splitTextToSize(ParrafoJSON.Obligaciones3, 540);
    doc.text(startX, startY += space + lineSpacing.NormalSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    lines = doc.splitTextToSize(ParrafoJSON.Obligaciones4, 540);
    doc.text(startX, startY += space + lineSpacing.NormalSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    doc.addImage(company_logo.srcf, 'PNG', startX + 400, startY += 50, company_logo.wf, company_logo.h);

    doc.addPage();
    startY = 0;

    doc.addImage(company_logo.src, 'PNG', startX, startY += 50, company_logo.w, company_logo.h);

    doc.setFontSize(fontSizes.NormalFontSize);
    lines = doc.splitTextToSize(ParrafoJSON.Obligaciones5, 540);
    doc.text(startX, startY += lineSpacing.NormalSpacing + company_logo.h, lines);
    space = (lines.length + 2) * lineSpacing.LineSpacing;

    lines = doc.splitTextToSize(ParrafoJSON.Obligaciones6, 540);
    doc.text(startX, startY += space + lineSpacing.NormalSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    lines = doc.splitTextToSize(ParrafoJSON.Obligaciones7, 540);
    doc.text(startX, startY += space + lineSpacing.NormalSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    lines = doc.splitTextToSize(ParrafoJSON.Obligaciones8, 540);
    doc.text(startX, startY += space + lineSpacing.NormalSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    doc.setFontType('bold');
    doc.text(startX, startY += space + lineSpacing.DobleSpacing, "Cuarto: Daños y pérdidas.");
    doc.setFontType('normal');
    lines = doc.splitTextToSize(ParrafoJSON.Daños, 540);
    doc.text(startX, startY += lineSpacing.NormalSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    doc.setFontType('bold');
    doc.text(startX, startY += space + lineSpacing.DobleSpacing, "Quinto: Restitución del equipo.");
    doc.setFontType('normal');
    lines = doc.splitTextToSize(ParrafoJSON.Restitucion, 540);
    doc.text(startX, startY += lineSpacing.NormalSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    doc.setFontType('bold');
    doc.text(startX, startY += space + lineSpacing.DobleSpacing, "Sexto: .");
    doc.setFontType('normal');
    lines = doc.splitTextToSize(ParrafoJSON.Final, 540);
    doc.text(startX, startY += lineSpacing.NormalSpacing, lines);
    space = (lines.length * lineSpacing.LineSpacing) + lineSpacing.DobleSpacing;

    doc.setFontType('bold');
    doc.text(startX + 100, startY += space + lineSpacing.DobleSpacing, "Alfredo Schaub R");
    doc.text(startX + 325, startY, data.nombre + ' ' + data.apellido);
    doc.setFontType('normal');
    doc.text(startX + 100, startY += lineSpacing.NormalSpacing, "Pp. Fabrimetal S.A");
    doc.text(startX + 325, startY, data.num_documento);

    doc.addImage(company_logo.srcf, 'PNG', startX + 400, startY += 50, company_logo.wf, company_logo.h);

    doc.save('AnexoContratoMovil' + data.idasignacion + '.pdf');

  });
}

function acta(idasignacion) {

  $.post("../ajax/asignacion.php?op=pdfacta", { idasignacion: idasignacion }, function (data, status) {
    data = JSON.parse(data);
    tabla.ajax.reload();

    if (data.estado == 1) {
      var condicion = 'Nuevo';
    } else {
      var condicion = 'Usado';
    }

    var ParrafoJSON = {
      Inicio: 'Al funcionario(a) identificado(a) en la presente acta, se le hace entrega del siguiente equipamiento telefónico, que deberá ser devuelto a la empresa en las mismas condiciones de usabilidad en el cual fue entregado.',
      Jurada: 'Yo, ' + data.nombre + ' ' + data.apellido + ', cédula de identidad número ' + data.num_documento + ', mediante este acto recibo conforme a lo indicado un móvil telefónico entregado por Fabrimetal S.A. para ser usado en el desempreño de mis funciones. Me comprometo a su correcto, oportuno y adecuado uso y cuidado, así como reportar en forma inmediata desgaste acelerado, pérdida o extravío.',
      Jurada2: 'Acuerdo formalmente que me sean descontados los gastos provocados por acceder a servicios no habilitados para mi uso: llamadas al 007, descargas de musica o juegos, bolsas de internet, y en general cualquier servicio qué no esté expresamente habilitado para mi uso.',
      Final: 'Asimismo, en caso de finiquito de mi contrato de trabajo, me comprometo a su devolución, sin desmedro del estado en que se encuentre, otorgando a la empresa el derecho de proceder a su descuento en el caso de que esto no ocurra. La valorización de dicho elementos para su descuento está establecida en el procedimiento interno (Reglamento de Orden, Higiene y Seguridad, Titulo XVII, Art. 56, Letra T).'
    };

    var Mes = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

    var company_logo = {
      srcf: 'data:image/jpeg;base64,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',
      src: 'data:image/jpeg;base64,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',
      w: 200,
      h: 40,
      wf: 127
    };

    var fontSizes = {
      HeadTitleFontSize: 18,
      Head2TitleFontSize: 16,
      TitleFontSize: 14,
      SubTitleFontSize: 12,
      NormalFontSize: 10,
      SmallFontSize: 8
    };

    var lineSpacing = {
      NormalSpacing: 12,
      DobleSpacing: 24,
      LineSpacing: 10,
      LetterSpace: 6
    };


    var doc = new jsPDF('p', 'pt', 'letter');

    var rightStartCol1 = 400;
    var rightStartCol2 = 480;


    var InitialstartX = 40;
    var startX = 40;
    var InitialstartY = 50;
    var startY = 0;
    var lines = 0;
    var space = 0;

    doc.addImage(company_logo.src, 'PNG', startX, startY += 50, company_logo.w, company_logo.h);

    doc.setFontSize(fontSizes.NormalFontSize);

    doc.setFontType('bold');
    doc.text(startX, startY += 15 + company_logo.h, "Cod Documento: ");
    doc.setFontType('normal');
    doc.text(startX + 85, startY, "FF-SS-AC-" + data.idasignacion);
    doc.setFontType('bold');
    doc.setFontSize(fontSizes.TitleFontSize);
    doc.myText("ACTA DE ENTREGA DE TELÉFONO MÓVIL", { align: "center" }, startX, startY += lineSpacing.DobleSpacing);

    doc.setFontSize(fontSizes.NormalFontSize);
    doc.setFontType('bold');
    doc.text(startX, startY += lineSpacing.DobleSpacing, "Fecha: ");
    doc.setFontType('normal');
    doc.text(startX + 35, startY, data.dia + ' de ' + Mes[data.mes - 1] + ' del ' + data.año);

    doc.setFontType('bold');
    doc.text(startX, startY += space + lineSpacing.NormalSpacing, "Nombre del funcionario: ");
    doc.setFontType('normal');
    doc.text(startX + 120, startY, data.nombre + ' ' + data.apellido);

    doc.setFontType('bold');
    doc.text(startX, startY += space + lineSpacing.NormalSpacing, "RUT: ");
    doc.setFontType('normal');
    doc.text(startX + 25, startY, data.num_documento);

    doc.setFontType('bold');
    doc.text(startX, startY += space + lineSpacing.NormalSpacing, "Cargo: ");
    doc.setFontType('normal');
    doc.text(startX + 35, startY, data.cargo);


    doc.setFontType('normal');
    lines = doc.splitTextToSize(ParrafoJSON.Inicio, 540);
    doc.text(startX, startY += lineSpacing.DobleSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;


    doc.setFontSize(fontSizes.SubTitleFontSize);
    doc.setFontType('bold');
    doc.text(startX, startY += space + lineSpacing.DobleSpacing, "Equipo ");


    doc.setFontSize(fontSizes.NormalFontSize);
    doc.setFontType('bold');
    doc.text(startX, startY += lineSpacing.DobleSpacing, "Tipo: ");
    doc.setFontType('normal');
    doc.text(startX + 25, startY, data.tipo);

    doc.setFontType('bold');
    doc.text(startX + 150, startY, "Marca: ");
    doc.setFontType('normal');
    doc.text(startX + 150 + 35, startY, data.marca);

    doc.setFontType('bold');
    doc.text(startX + 300, startY, "Modelo: ");
    doc.setFontType('normal');
    doc.text(startX + 300 + 40, startY, data.equipo);

    doc.setFontType('bold');
    doc.text(startX, startY += lineSpacing.DobleSpacing, "Color: ");
    doc.setFontType('normal');
    doc.text(startX + 35, startY, data.color);
    doc.setFontType('bold');
    doc.text(startX + 150, startY, "IMEI: ");
    doc.setFontType('normal');
    doc.text(startX + 150 + 30, startY, data.imei);
    doc.setFontType('bold');
    doc.text(startX + 300, startY, "Condición: ");
    doc.setFontType('normal');
    doc.text(startX + 300 + 55, startY, condicion);

    doc.setFontType('bold');
    doc.text(startX, startY += lineSpacing.DobleSpacing, "Accesorios: ");
    doc.setFontType('normal');
    doc.text(startX + 60, startY, "Cable USB, cargador, auriculares");


    doc.setFontSize(fontSizes.SubTitleFontSize);
    doc.setFontType('bold');
    doc.text(startX, startY += space + lineSpacing.NormalSpacing, "Tarjeta SIM ");

    doc.setFontSize(fontSizes.NormalFontSize);
    doc.setFontType('bold');
    doc.text(startX, startY += lineSpacing.DobleSpacing, "Operador: ");
    doc.setFontType('normal');
    doc.text(startX + 50, startY, data.operador);
    doc.setFontType('bold');
    doc.text(startX + 150, startY, "Número: ");
    doc.setFontType('normal');
    doc.text(startX + 150 + 45, startY, data.numero);
    doc.setFontType('bold');
    doc.text(startX + 300, startY, "Serial SIM: ");
    doc.setFontType('normal');
    doc.text(startX + 300 + 60, startY, data.serial);

    doc.setFontType('bold');
    doc.text(startX, startY += lineSpacing.DobleSpacing, "Paquete Datos: ");
    doc.setFontType('normal');
    doc.text(startX + 75, startY, '2.4 Gb');
    doc.setFontType('bold');
    doc.text(startX + 150, startY, "Telefonía interna: ");
    doc.setFontType('normal');
    doc.text(startX + 150 + 85, startY, 'Ilimitada');
    doc.setFontType('bold');
    doc.text(startX + 300, startY, "Telefonía: ");
    doc.setFontType('normal');
    doc.text(startX + 300 + 50, startY, '150 Minutos');

    doc.setFontType('bold');
    doc.text(startX, startY += lineSpacing.DobleSpacing, "Telefonía Internacional: ");
    doc.setFontType('normal');
    doc.text(startX + 120, startY, 'N/A');

    doc.setFontSize(fontSizes.SubTitleFontSize);
    doc.setFontType('bold');
    doc.text(startX, startY += lineSpacing.DobleSpacing, "Valorización: ");
    doc.setFontType('normal');
    doc.text(startX + 80, startY, '$ ' + data.precio);

    doc.setFontSize(fontSizes.NormalFontSize);
    doc.setFontType('normal');
    lines = doc.splitTextToSize(ParrafoJSON.Jurada, 540);
    doc.text(startX, startY += lineSpacing.DobleSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    lines = doc.splitTextToSize(ParrafoJSON.Jurada2, 540);
    doc.text(startX, startY += space + lineSpacing.DobleSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    lines = doc.splitTextToSize(ParrafoJSON.Final, 540);
    doc.text(startX, startY += space + lineSpacing.DobleSpacing, lines);
    space = lines.length * lineSpacing.LineSpacing;

    doc.setFontType('bold');
    doc.text(startX, startY += space + lineSpacing.DobleSpacing, "Entregado por: ");
    doc.setFontType('bold');
    doc.text(startX + 250, startY, "Recibido por: ");

    doc.setFontType('bold');
    doc.text(startX, startY += lineSpacing.DobleSpacing, "RUT: ");
    doc.setFontType('bold');
    doc.text(startX + 250, startY, "RUT: ");

    doc.setFontType('bold');
    doc.text(startX, startY += lineSpacing.DobleSpacing, "FIRMA: ");
    doc.setFontType('bold');
    doc.text(startX + 250, startY, "FIRMA: ");



    doc.addImage(company_logo.srcf, 'PNG', startX + 400, 676 + 50, company_logo.wf, company_logo.h);

    doc.save('EntregaMovil' + data.idasignacion + '.pdf');

  });
}

function devolucion(idasignacion) {

  bootbox.prompt({
    title: "Motivo de la Devolución",
    //inputType: 'textarea',
    inputType: 'select',
    inputOptions: [
      {text: 'Seleccione',value: 0,},
      {text: 'ROBO',value: 'Robo',},
      {text: 'DESPIDO',value: 'Despido',},
      {text: 'RENUNCIA',value: 'Renuncia',},
      {text: 'REPOSICIÓN',value: 'Reposicion',}
    ],
    value: 0,
    callback: function (result) {
      console.log(result);

      //if (result !== null) {

        if (result != 0) {

          $.post("../ajax/asignacion.php?op=pdfdev", { "idasignacion": idasignacion, "detalle": result }, function (data, status) {
            data = JSON.parse(data);
            tabla.ajax.reload();

            if (data.estado == 1) {
              var condicion = 'Nuevo';
            } else {
              var condicion = 'Usado';
            }

            var ParrafoJSON = {
              Inicio: 'Yo, FRANCISCO SANDOVAL ROSENTHAL, cédula de identidad nacional 8.325.484-K, en mi calidad de Jefe de Sistemas y Servicios, declaro recibir en este acto del Sr(a): ' + data.nombre + ' ' + data.apellido + ' cédula de identidad número  ' + data.num_documento + ',  el siguiente elemento a su cargo:',
              Final: 'Se deja constancia que no existen, con este departamento, cargos pendientes con el Sr(a): ' + data.nombre + ' ' + data.apellido + ''
            };

            var Mes = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

            var company_logo = {
              srcf: 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wgARCAAoAH8DAREAAhEBAxEB/8QAHAAAAgIDAQEAAAAAAAAAAAAAAAUEBwIDBgEI/8QAGgEAAgMBAQAAAAAAAAAAAAAAAAMCBAYFAf/aAAwDAQACEAMQAAABtDTZ/iuvzWKHLnpYocAuenrOZfuLK6MAAAAAACm9XnKy0PEbVnxWQYIdHZCG1XRULlz5PSc7foxGezEz99Vj4/rubbaVrNd93j173eRLVOMyDqpZ3rnHZD09uHK6OC2EVid0WYSVol6+pv3wZ8473G1TpeC9p2kVyq9p2gEVyrY/B7P0hgdmltV5ipa/ZaGJy8m5qulqaAAAAAAAAAAAAAAAB//EACUQAAICAgEDAwUAAAAAAAAAAAQFAwYBAgATFzUHFRYiMDQ2QP/aAAgBAQABBQK2WwtCx7jsuH35gLP3HZcZX5gGxW35gYx7jsuVi4mOmv2LGi+QWr470UEdf3clsaxHosMrE7TZUi6ESyuQFp6kOKNaHsmIl2Sth9tHE5BMDuaPT3mbeUBvP01c8hS7hJcAd0ZHjWpAGaISLtuFV0GjvChcW8CcDU5lELX1v13omfAo8LKPaHZ4NHjLaDqYeB5xE/Gl3gn0Ji56j+c45/L4984i85ygfsJYcZ0cleGzrIq0kyQminh3RaSz4Rx68FG1Eg/h/8QAOBEAAQIDBAUJBgcAAAAAAAAAAQIDAAQREiExUQUTQWFxEBQiNIGhwdHwFTKCkbHhIzBAQlKy8f/aAAgBAwEBPwGfn3ZV0IQBhHtmYyHf5w7pZ9CqADAd4rnHtmYyHf5w9pZ9t1SABcfW2GdLPuOpQQLz62x7ZmMh3+cSOkXZl7VrA/JnJXnc4EVp0fGOZ2ZfnDhpkKYwJQzK7RNEgJv+EQ9JANa9hdpMOSK39a8jYo3QxK2QzMVxULu37QzJocY17i7I4VjR6G0TYDarQplSJo2Wqk0vH1EKcKFfgmqdmJvsr7TgLr918NvuOKSon+XA4YUUd/yMGYWlZCb7/l0gL+ypGGG2BNrKjeAMzgBVYrjTYMseAjXqTYFcaZ1NcacNt1RiaCJZanWUuKxIryLcQ3PVWadDxh51uflgpSqLT3w262tCpV02ahN/wjyglmRl1oSu0peUCZEu08UnpWzd2iHJpqYSzYuNsXRo55KJazbANdsM36QtWgqo2QpQQCo7IQu0L8fXobo5yitPA7vOC8gf4Y5010b/AHsLjthMy2sFQwuz2whYcFpPJpnrA4eJ5Jn3xwT/AFHJNdYc4n6xK9Yb4j68mies9kONpdTZWLo5sj9nRG671XwFL41AraqfVPIb98OMJcQUYQZRKlBZJqKZbDUbM8oTLhIs1O7dTL71rgaiG2w2myP0X//EADkRAAECAwUCCgkFAQAAAAAAAAECAwAEEQUSITFBUWEQFCI0coGhsdHwExUyUnGCkcHhMDNAQvGy/9oACAECAQE/AbMsxmdZLjhOdMOrdHqCW95XZ4QxYku4mpUcyNNCRsj1BLe8rs8Il7El3WUOFRxAOnhExYku0ytwKOAJ08I9QS3vK7PCLRspmUY9Kgmvnd+jITnEZEuXa8unZHH781xVpNaZmuUKnkyjd0C8oqVQfMYYtFRe4tMouK74atFuWDLDmRSnHz3xMzl9T8rd9lBNer8xMT7jUwJZtu8aVzpFprdckSXUXTXbWJNN56gFcFb/AOpphAbCxR8UVrQAYXkU3A4nZpXCFSrbbbgAxw6sFZ1SPJTBlEKxoRsyx5JOHXQa50wMcUQEgEEnYKVJog7DtO3L4mHpVurigMr2OgpkKb9Mdd0TKEtPLbRkDTgbaW7ZtG015enRiXZcs2bKUoq2vZp5/O6HWXW1pnWU3rpUCPmV4wA/aM2hxbZQlGOMGUM09LhSTduDHqMNSb8qt/0mIuHHz3RasutybCvRqUmmn+GJjCy7lwpodc9uwQ2guLCBrC2FBVE45duUCTdV7NNNRr1xxZdAdu8duOEcTe5Qp7OeI8YVJOIArnjqMKUzNaDOFoU2q6rgsDmyul9hwSn7Z6Sv+jwSXNmuiO6J3mzvRPdwW3zQ/EQ06plV5GcCedwK+URt+o+n3hMyUilB/mR6q/DaDCJpSFX6V/Ap53wmdUhJQlIoa7dRQ6wZxRzSNa760rX6aUppDjhdVeP8L//EADsQAAEDAgMDCAgDCQAAAAAAAAECAxEEEgATIQUxQRAjMkJRYXGRFCIzdIGSstIVMMFAYnOCobGz0fD/2gAIAQEABj8CbYYbZWhTQXzgM7z392PYUvyq+7CUJZpiC02vVKusgKPHvx7Cl+VX3YqmEM0xQ06pAlKp0PjilYWzTBDrqUGEqnU+OPYUvyq+7Apn22EoKSZbSZ/v+Sinzsi2jzLrbuuf94/E6p5VPeYZZy5LnZx0wpxTqaWkZpmC4+vhzKcHaOzqwV1Kkwv1bVIxteup1XrarHU5EakTOnnuxsXamdOdWIbyrd3rHj/Lhe0amv8ARGkrsPMlf69+G00lX6a3kqJXllEHsg4kuFlGa0FLC7ITmJnXhpgmidL9Mo82464txN+W4TrMkaJ7ePHFEsupCFXIiLUuG9vo2rIO/t4KwkFxt5CQkuGwgsi9I9Yz2FRnTozuwohbVOibS68lRSkXvASJEdBPn4YoWytClKQzzSkkuOhQErCp4a8Or34pnnrcxxAWbBA15L33m2EHZ8XOKtHtMNuO1SKfaNGDKHVAZvbHjH/b8VOxqx4UiX2GHEPndOUjf8o/risYZrWq+rrRZzRlKRHj3nG3FNvNJqxtFaktKIlQuTOnnjYxpyltz8RbUtjrJMmT5nf34W36bS01RnEgVCuGnCRjO9KYq1OsXFVN0RpEbz2YcdUCQgTCd57hi9zm1ALvTvi0wrCszNTCljRlaujvOg3ajXCki9Vsza0s7iBppr8MUxvWE1EZSlNLAVO7WMORmZYShSTlLuXdduTEno8P0wHGzKT8ORj3cfUrkb93Y/xJ5Noe8OfUcbP94b+ociP4asBt4XtXBRQdyvHC0sldIhcyliEjUQeHHTyweddRJM2nqmLk/GPHsIwW8xxsG7oR1lBRG7ujww285UPuOJtkm0XWquEwMGH37haG1SJbCZiNP3iNZwGkSQJMq3kkyT5/sX//xAAnEAEBAAICAgEDAwUAAAAAAAABEQAhMUFRYRBxgZEwQNGxweHw8f/aAAgBAQABPyF60FfSfQ1p8bHVA96/Rmr42O8B7goXz1jvAeYCM89/GyW1uNH1f6PVe9lCkp/xn3zGQbWIdvHBd0weoQU6IavvevxUANC+AbPvvRpHjeWxajVUV2+j+M+h3zru239neaeP3oR1X4ZV6f4jQRvvGF4XJA1oindziShnJNB7wi66av6WPYFRg+3GnMZBG1hNtqTonIrhP6QM9EyYXW43oDBF+lB4pu3KpGJE69jFgK8Wfx8AogRo9K96cks6RsK47ik4dSJiYE1AF4ln4LpRmXmsy1Zs0hs7U1BzRnM+v5USKe/Gb0XSCEl2NPL2phUWEU022vPeIKkuWj6ohee8eI2a+g7XgM5e82dMs2DxoojMVBpnQMP7aG+cFNzvFUJHqu61/SoOhVqioFig9b4TIJ5MQUGqSqEl8s2SdyKEYiOxERHYn6M1fYsf6nxmquDDPYDssZ6OqZ0SAex5wgudlI2vRrUZfCNCqmjehmhot6YAoToSUjc8vMYqMOqyEOHkHLYQdTJ92kcw7uJowjVQh8qX7/sv/9oADAMBAAIAAwAAABDqKReSSSSarQuxL/TR0/wbR5xZZLQcbSySSSSSSST/xAAmEQEBAQACAgECBgMAAAAAAAABESEAMUFRYXGhEIGRsfDxIDBA/9oACAEDAQE/EHTBDo+08J65/R8CW2vT4F+pzn9HwkvoFHwpxJfAYPlDj+j4DgCLg3Pqv+nxTXZfI9nvnZds0Po9kHfHRS0r0NsvH6F+dw/IVsE7kT8v3wyMTeMtqHGoNo+Xep+dzndubHWvN/Z544hGdvT0/PrmjMbWvUf3+eHgGrZBJ0SZa3g2zdlNCMReliL03BQBA5oDKAp5VYMNRZgCuNIyrhdAJRA8Q4DGkBljUradKAdaUZRURKnSooCCDyMsWCGlmr19fwg+MVQ+/hgTuKHTZ8sydOSI8lFOPCmLc8PV0ozgksnkBJscxY+VMg8FSBBJU0zuJevn1xOmi4o1rPIrb5u7Tj4V3bxnij9+TvlV6mSdvgvfnnQSFfOGuc65AonyX6MQqQqGFnJpXuERYJYFhBenG6cqSslidlJBuazo1mXNqiBYKoFk3xfG9bxwVAqFNIISozEo76eBUzfCdMcYiJEfw+x/4Cj+S9ufyXp+HT9XEliin0RPuFPJjRTiWJoMkLEfFwxidkCq0ifhDIEMpbKQJg5QKH1OpJokmScYW7GKQmCB8C5VhF4lhoz0IDrzoNMOEkXtV7VVV6NVYAHQBD/i/8QAJhEBAQEAAwABAwMFAQAAAAAAAREhADFBURBhgaHw8SAwQHGRsf/aAAgBAgEBPxAxJFoBAXq+efynBPdMT0E/4N+/P5ThTFYjFQc4pikVigu8fynBevQak3/Q/s+6qizstsfjnRUrkI7JGph32xkUANjTt93ufbGv5QnSlNp+X/motKJEh4tvBSRPDPWekrzpt2DvGSZ+XnKk/wAPy+SefPMx9yMfNPyT7cbQqAhSJoI7IR3mLOYTsKEEYrU0vVCoruo8SwUMQtB6p1Ib2Q4pDwKVX4DjPBUSg4GiFoYKJohQCyQdNY7gFBgBob0VaRcZcDuWTOt7+iZj3ArPWcXc0iFdsveFjezDR4taesR2A31e5jEHn2VRitpKFqFOgHahxwxkgYNDeqMY/aiPBQsM3EgBfECfAMyPGZAmXvfYfpysimHdXXwVTrziKgoN6L6vgevhzpUcOFm+sfnUES8k6UWn7ho1jnedcHZDEo9i9hoMoXzeJsi2gdAisKQupY52Jw4ikJh2OwWIojDtOKBifnsoiYiaJiaZ9P1T+gD/ALT8OftPy+uKTkIPpcU+8pfLSMSwJ1G1x+QtWXxDSBSG67HtPpqERqOxvC8ETu9oGiN20RAJOAf9DU3irZ61NDFFMwNCMejWaEwWg4/mOEOgAAPsABa5qv8Ahf/EACIQAQEAAgIBBQADAAAAAAAAAAERACExQVEQMGFxkUDR8P/aAAgBAQABPxA58zUxBPp0Wrv0HPWvERIQ6GO4FV24OWNs4mSCMCwC9GDG2cTJLCFKJen0HLJqwNEKSb8ez/UF3Hd3t+m9FHyUDF+pWVx9Buj1e6FQWh0AatRiDYPWGitYhpQIUg3qHrNHXhBsYxWL8v8AMXP+ifnrdAi6FddFsghp5zmMMvaNisHQ49OKvjZl5UAUCC7xTzaxNCVrgGAZ9CKnzm1ookdETlgYhqObE9ANQmEnFmlAjHSW3VmHkdf5OAHJX7BB9LmC5Y9AldosqGCfAoi0Kgwk5g+MV4Le5dhABpWMBU5ep+NAqg6Nkt6cgsE92xiRBtQ0sZJRAmFYKAhGhSzdrhBnGUi2iCfejcqqyIAQFO5MQHHPuElwJX6QmP8At9DCnYU7Ch3lCBuzWoCwFUIbQW9V3O7Br+CpGGFbJ0UWMKTgoiuH2nNmGHsQCwBQk1akKqYM9wT0JjtpQqQHgYEICCJ7IaDWrf7Xhg/SooNcMTxqk1IsYYahmoDhhElGBnem+YW0XcOgOA2MRqtJSoTEEq5KKFUBJ0iKwCCTvG7W6VOoeUkdK+fURnQ5XgFUAh/C/9k=',
              src: 'data:image/jpeg;base64,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',
              w: 200,
              h: 40,
              wf: 127
            };

            var fontSizes = {
              HeadTitleFontSize: 18,
              Head2TitleFontSize: 16,
              TitleFontSize: 14,
              SubTitleFontSize: 12,
              NormalFontSize: 10,
              SmallFontSize: 8
            };

            var lineSpacing = {
              NormalSpacing: 12,
              DobleSpacing: 24,
              LineSpacing: 10,
              LetterSpace: 6
            };


            var doc = new jsPDF('p', 'pt', 'letter');

            var rightStartCol1 = 400;
            var rightStartCol2 = 480;


            var InitialstartX = 40;
            var startX = 40;
            var InitialstartY = 50;
            var startY = 0;
            var lines = 0;
            var space = 0;

            doc.addImage(company_logo.src, 'PNG', startX, startY += 50, company_logo.w, company_logo.h);

            doc.setFontSize(fontSizes.NormalFontSize);

            doc.setFontType('bold');
            doc.text(startX, startY += 15 + company_logo.h, "Cod Documento: ");
            doc.setFontType('normal');
            doc.text(startX + 85, startY, "FF-SS-AC-" + data.idasignacion);
            doc.setFontType('bold');
            doc.setFontSize(fontSizes.TitleFontSize);
            doc.myText("ACTA DE DEVOLUCIÓN DE TELÉFONO MÓVIL", { align: "center" }, startX, startY += lineSpacing.DobleSpacing);

            doc.setFontSize(fontSizes.NormalFontSize);
            doc.setFontType('bold');
            doc.text(startX, startY += lineSpacing.DobleSpacing, "Fecha: ");
            doc.setFontType('normal');
            doc.text(startX + 35, startY, data.dia + ' de ' + Mes[data.mes - 1] + ' del ' + data.año);


            doc.setFontType('normal');
            lines = doc.splitTextToSize(ParrafoJSON.Inicio, 540);
            doc.text(startX, startY += lineSpacing.DobleSpacing, lines);
            space = lines.length * lineSpacing.LineSpacing;


            //Funcionario
            doc.setFontSize(fontSizes.SubTitleFontSize);
            doc.setFontType('bold');
            doc.text(startX, startY += space + lineSpacing.DobleSpacing, "Funcionario ");
            doc.setFontSize(fontSizes.NormalFontSize);
            doc.setFontType('bold');
            doc.text(startX, startY += lineSpacing.DobleSpacing, "Nombres y Apellidos: ");
            doc.setFontType('normal');
            doc.text(startX + 105, startY, data.nombre + ' ' + data.apellido);
            doc.setFontType('bold');
            doc.text(startX, startY += lineSpacing.DobleSpacing, "RUT: ");
            doc.setFontType('normal');
            doc.text(startX + 25, startY, data.num_documento);
            doc.setFontType('bold');
            doc.text(startX, startY += lineSpacing.DobleSpacing, "Cargo: ");
            doc.setFontType('normal');
            doc.text(startX + 35, startY, data.cargo);

            //Equipo
            doc.setFontSize(fontSizes.SubTitleFontSize);
            doc.setFontType('bold');
            doc.text(startX, startY += lineSpacing.DobleSpacing + lineSpacing.LineSpacing, "Equipo ");
            doc.setFontSize(fontSizes.NormalFontSize);
            doc.setFontType('bold');
            doc.text(startX, startY += lineSpacing.DobleSpacing, "Tipo: ");
            doc.setFontType('normal');
            doc.text(startX + 25, startY, data.tipo);
            doc.setFontType('bold');
            doc.text(startX + 150, startY, "Marca: ");
            doc.setFontType('normal');
            doc.text(startX + 150 + 35, startY, data.marca);
            doc.setFontType('bold');
            doc.text(startX + 300, startY, "Modelo: ");
            doc.setFontType('normal');
            doc.text(startX + 300 + 40, startY, data.equipo);
            doc.setFontType('bold');
            doc.text(startX, startY += lineSpacing.DobleSpacing, "Color: ");
            doc.setFontType('normal');
            doc.text(startX + 35, startY, data.color);
            doc.setFontType('bold');
            doc.text(startX + 150, startY, "IMEI: ");
            doc.setFontType('normal');
            doc.text(startX + 150 + 30, startY, data.imei);
            doc.setFontType('bold');
            doc.text(startX + 300, startY, "Condición: ");
            doc.setFontType('normal');
            doc.text(startX + 300 + 55, startY, condicion);
            doc.setFontType('bold');
            doc.text(startX, startY += lineSpacing.DobleSpacing, "Accesorios: ");
            doc.setFontType('normal');
            doc.text(startX + 60, startY, "Cable USB, cargador, auriculares");

            //Tarjeta SIM
            doc.setFontSize(fontSizes.SubTitleFontSize);
            doc.setFontType('bold');
            doc.text(startX, startY += space + lineSpacing.NormalSpacing, "Tarjeta SIM ");
            doc.setFontSize(fontSizes.NormalFontSize);
            doc.setFontType('bold');
            doc.text(startX, startY += lineSpacing.DobleSpacing, "Operador: ");
            doc.setFontType('normal');
            doc.text(startX + 50, startY, data.operador);
            doc.setFontType('bold');
            doc.text(startX + 150, startY, "Número: ");
            doc.setFontType('normal');
            doc.text(startX + 150 + 45, startY, data.numero);
            doc.setFontType('bold');
            doc.text(startX + 300, startY, "Serial SIM: ");
            doc.setFontType('normal');
            doc.text(startX + 300 + 60, startY, data.serial);
            doc.setFontType('bold');
            doc.text(startX, startY += lineSpacing.DobleSpacing, "Paquete Datos: ");
            doc.setFontType('normal');
            doc.text(startX + 75, startY, '2.4 Gb');
            doc.setFontType('bold');
            doc.text(startX + 150, startY, "Telefonía interna: ");
            doc.setFontType('normal');
            doc.text(startX + 150 + 85, startY, 'Ilimitada');
            doc.setFontType('bold');
            doc.text(startX + 300, startY, "Telefonía: ");
            doc.setFontType('normal');
            doc.text(startX + 300 + 50, startY, '150 Minutos');
            doc.setFontType('bold');
            doc.text(startX, startY += lineSpacing.DobleSpacing, "Telefonía Internacional: ");
            doc.setFontType('normal');
            doc.text(startX + 120, startY, 'N/A');

            doc.setFontSize(fontSizes.SubTitleFontSize);
            doc.setFontType('bold');
            doc.text(startX, startY += lineSpacing.DobleSpacing + lineSpacing.LineSpacing, "Motivo: ");
            doc.setFontSize(fontSizes.NormalFontSize);
            doc.setFontType('normal');
            doc.text(startX + 50, startY, result);

            doc.setFontSize(fontSizes.NormalFontSize);
            doc.setFontType('normal');


            lines = doc.splitTextToSize(ParrafoJSON.Final, 540);
            doc.text(startX, startY += lineSpacing.DobleSpacing, lines);
            space = lines.length * lineSpacing.LineSpacing;

            doc.setFontType('bold');
            doc.text(startX, startY += space + lineSpacing.DobleSpacing + lineSpacing.LineSpacing, "Entregado por: ");
            doc.setFontType('bold');
            doc.text(startX + 250, startY, "Recibido por: ");

            doc.setFontType('bold');
            doc.text(startX, startY += lineSpacing.DobleSpacing, "RUT: ");
            doc.setFontType('bold');
            doc.text(startX + 250, startY, "RUT: ");

            doc.setFontType('bold');
            doc.text(startX, startY += lineSpacing.DobleSpacing, "Firma: ");
            doc.setFontType('bold');
            doc.text(startX + 250, startY, "Firma: ");

            doc.addImage(company_logo.srcf, 'PNG', startX + 400, 676 + 50, company_logo.wf, company_logo.h);

            doc.save('DevolucionMovil' + data.idasignacion + '.pdf');
          });

        } else {
          new PNotify({
            title: 'Error!',
            text: 'Debe indicar el Motivo de la Devolución.',
            type: 'error',
            styling: 'bootstrap3'
          });
        }
      //}
    }
  });
}


init();