var tabla;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardar(e);	
	})
}

//Función limpiar
function limpiar()
{
	$("#idregistro").val("");
	$("#ano").val("");
	$("#codigo").val("");
	$("#denominacion").val("");
	$("#costoinicial").val("");
	$("#saldoinicial").val("");
	$("#valorinicial").val("");
	$("#compras").val("");
	$("#ventas").val("");
	$("#saldofinal").val("");
	$("#costo").val("");
	$("#valorfinal").val("");
	document.getElementById("btnGuardar").innerHTML = "Agregar";
	
}

//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

//Función Listar
function listar()
{
	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
						// 'copyHtml5',
						// 'excelHtml5',
						// 'csvHtml5',
						// 'pdf'
		        ],
		"ajax":
				{
					url: '../ajax/registroinventario.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 10
	    
	}).DataTable();
}
//Función para guardar o editar

function guardar(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/registroinventario.php?op=guardar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function(datos) {
			swal.fire({
				title: 'Éxito',
				text: datos,
				icon: 'success',
				showConfirmButton: false,
  				timer: 1500
			});
			mostrarform(false);
			tabla.ajax.reload();
		}
	});
	limpiar();
}

function mostrar(idregistro)
{

	$.post("../ajax/registroinventario.php?op=mostrar",{idregistro : idregistro}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);
		$("#idregistro").val(data.idregistro);
		$("#ano").val(data.ano);
		$("#codigo").val(data.codigo);
		$("#denominacion").val(data.denominacion);
		$("#costoinicial").val(data.costoinicial);
		$("#saldoinicial").val(data.saldoinicial);
		$("#valorinicial").val(data.valorinicial);
		$("#compras").val(data.compras);
		$("#ventas").val(data.ventas);
		$("#saldofinal").val(data.saldofinal);
		$("#costo").val(data.costo);
		$("#valorfinal").val(data.valorfinal);
		$('#agregarinventario').modal('show');
		document.getElementById("btnGuardar").innerHTML = "Actualizar";
 	})
}


function eliminar(idregistro)
{
	swal.fire({
	  title: "¿Está seguro?",
	  text: "¿Desea eliminar este registro?",
	  icon: "warning",
	  showCancelButton: true,
	  confirmButtonColor: "#3085d6",
	  cancelButtonColor: "#d33",
	  confirmButtonText: "Sí, eliminar",
	  cancelButtonText: "Cancelar"
	}).then((result) => {
	  if (result.isConfirmed) {
	    $.post("../ajax/registroinventario.php?op=eliminar", {idregistro : idregistro}, function(e){
		  swal.fire({
		    title: "Eliminado",
		    text: e,
		    icon: "success",
			showConfirmButton: false,
  			timer: 1500
		  });
	      tabla.ajax.reload();
	    });
	  }
	});
}




function refrescartabla()
{
tabla.ajax.reload();
listar();
}


init();