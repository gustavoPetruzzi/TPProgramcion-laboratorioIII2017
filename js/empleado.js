                            /* EMPLEADOS */
function registrado(data){
    if(data.exito){
        alert("empleado Registrado");
    }
}

function registrarEmpleado(){
    $('#agregarEmpleado').modal('show');
    $("#modificar").attr('id', 'register');
    $("#register").click(function(){
        var nombreNuevo = $("#nombreNuevo").val();
        var apellidoNuevo = $("#apellidoNuevo").val();
        var usuarioNuevo = $("#usuarioNuevo").val();
        var adminNuevo = false;
        if($('#adminNuevo').is(':checked')){
            adminNuevo = true;
        }
        var clave = $("#clave").val();
        $.ajax({
            url:"empleados/alta",
            headers: { token : localStorage.getItem('token')},
            type:"POST",
            dataType:'json',
            data: { 
                nombre: nombreNuevo,
                usuario: usuarioNuevo, 
                apellido: apellidoNuevo,
                pass: clave,
                activo: true,
                admin: adminNuevo
            }
        }).then(registrado,error);
    })
}



function traerEmpleados(empleados){
    
    $.ajax({
        url:'empleados/lista',
        headers: { token : localStorage.getItem('token')},
        type: "GET",
        dataType: 'json'
        
    }).then(empleados, error)
}

function tablaEmpleados(data){
    if(data.exito){
        var tabla = "<table class=' table table-striped' id='empleadosTable'> <thead> <tr> <td> Id </td> <td> Usuario </td> <td> Activo </td> <td> Borrar / Modificar </td> </tr> </thead>";
        tabla += "<tbody>";
        for (var element in data.empleados) {
            var empleado = data.empleados[element];
            if(empleado.activo == 1){
                empleado.activo = "Activo";
            }
            else{
                empleado.activo = "Suspendido";
            }
            tabla += "<tr> <td>" + empleado.id + "</td> <td>" + empleado.usuario + "</td> <td>" + empleado.activo + "</td>";
            tabla += "<td> <button type='button' class='btn btn-warning' onclick='modificarEmpleado(" + JSON.stringify(empleado) + ")'><span class='glyphicons glyphicons-random'></span> Modificar</button>";
            tabla += "<button type='button' class='btn btn-danger' onclick='borrarEmpleado(" + empleado.id + ")'><span class='glyphicons glyphicons-bin'></span> Borrar</button>  ";
            tabla += "<button type='button' class='btn btn-success' onclick='actualizarEmpleado(" + empleado.id + ")'><span class='glyphicons glyphicons-bin'></span> Actualizar</button>  </td>";
            
        }
        tabla+= "</tbody> </table>";

        $("#info").html(tabla);
        $("#info").ready(function(){
            $("#empleadosTable").DataTable();    
        });

    }
    else{
        $("#info").html("<h2> Usted no tiene los permisos para lo requerido </h2>");
    }
}
function borrarEmpleado(idEmpleado){
    $.ajax({
        url: 'empleados/borrar/' + idEmpleado,
        type:"DELETE",
        dataType: 'json',
        headers: { token : localStorage.getItem('token')},
        
    }).then(borrado, error)

}

function borrado(data){
    if(data.exito){
        var estado = "<h3 class='text-success text-center'> Empleado eliminado </h3>";
        estado+= "<p> <b> ID: </b>" + data.empleado.id + "</p>";
        estado+= "<p> <b> Usuario: </b>" + data.empleado.usuario + "</p>";

        $("#estado").html(estado);
        traerEmpleados();
    }
}

function modificarEmpleado(empleado){
    $("#idEmpleado").val(empleado.id);
    $("#usuarioNuevo").val(empleado.usuario);
    $("#nombreNuevo").val(empleado.nombre);
    $("#apellidoNuevo").val(empleado.apellido);
    $("#clave").val(empleado._pass);
    if(empleado.admin){
        $( "#adminNuevo" ).prop( "checked", true );
    }
    else{
        $( "#adminNuevo" ).prop( "checked", false );
    }

    
    $("#register").attr('id','modificar');

    $("#idEmpleado").removeClass("hidden");
    $('#agregarEmpleado').modal('show');
    $("#modificar").click(function(){
        var nombreModificado = $("#nombreNuevo").val();
        var apellidoModificado = $("#apellidoNuevo").val();
        var usuarioModificado = $("#usuarioNuevo").val();
        var passModificado = $("#clave").val();
        if($('#adminNuevo').is(':checked')){
            var adminModificado = true;
        }
        else{
            var adminModificado = false;
        }
        $.ajax({
            url:'empleados/modificar',
            type:'POST',
            dataType:'json',
            headers: { token : localStorage.getItem('token')},
            data: {
                id: empleado.id, 
                usuario: usuarioModificado,
                nombre: nombreModificado,
                apellido: apellidoModificado,
                activo: true,
                admin: adminModificado,
                pass :passModificado,
            }
        }).then(modificado, error);
        
    })
}

function modificado(data, status, xhr){
    if(xhr.status == 200){
        alert("Modificado");
        traerEmpleados();
    }
}

function logueosEmpleados(){
    $.ajax({
        url: 'empleados/logueos',
        headers: { token : localStorage.getItem('token')},
        type: "GET",
    }).then(tablaLogueos, error);
}

function tablaLogueos(data, status, xhr){
    
    if(xhr.status == 200){
        var tabla = "<table class=' table table-striped' id='logueosTable'> <thead> <tr> <td> Id </td> <td> Usuario </td> <td> Activo </td> <td> Entrada </td> <td> salida </td> </tr> </thead>";
            tabla += "<tbody>";
            for (var element in data) {
                var empleado = data[element];
                if(empleado.activo){
                    empleado.activo = "Activo";
                }
                else{
                    empleado.activo = "Suspendido";
                }
                tabla += "<tr> <td>" + empleado.id + "</td> <td>" + empleado.usuario + "</td> <td>" + empleado.activo + "</td> <td>" + empleado.entrada + "</td> <td>" + empleado.salida + "</td>";

            }
            tabla+= "</tbody> </table>";

            $("#info").html(tabla);
            $("#info").ready(function(){
                $("#logueosTable").DataTable();    
            });
            
    }
    

}

var timePicker = $(function () {
    $('#datetimepicker1').datetimepicker();
});

function actualizarEmpleado(idEmpleado){
    
    $.ajax({
        url:'empleados/actualizar/'+ idEmpleado,
        headers: { token : localStorage.getItem('token')},
        type:"PATCH",
        dataType: 'json',
        
    }).then(actualizado,error);
}
function actualizado(data){
    if(data.exito){
        var estado = "<h3 class='text-success text-center'> Empleado actualizado </h3>";
        estado+= "<p> <b> ID: </b>" + data.empleado.id + "</p>";
        estado+= "<p> <b> Usuario: </b>" + data.empleado.usuario + "</p>";

        $("#estado").html(estado);
        traerEmpleados();
    }
}

function modalEmpleado(){
    $('#agregarEmpleado').on('hidden.bs.modal', function (e) {
        $("[name=modalEmpleado]").prop('onclick',null).off('click');

        $("#modificar").attr('id', 'register');
        $("#idEmpleado").addClass("hidden");
        $("#usuarioNuevo").val("");
        $("#clave").val("");
        $("#nombreNuevo").val("");
        $("#apellidoNuevo").val("");

    })
}

function operaciones(){
    traerEmpleados(operacionesDatos);
}

function operacionesDatos(data,status, xhr){
    if(data.exito){
        var opciones= "<div class='form-group'>";
        opciones+= "    <label for='selectOperaciones'> id </label>";
        opciones+= "    <select class='form-control' id='selectOperaciones'> </select> </div>";
        opciones+= "    <label> Desde: </label>";
        opciones+= `<div class="container">
                        <div class="row">
                            <div class='col-sm-6'>
                                <div class="form-group">
                                    <div class='input-group date' id='datetimepicker1'>
                                        <input type='text' class="form-control" id='operacionesDesde' />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $('#datetimepicker1').datetimepicker({
                                        format: 'YYYY-MM-DD'
                                    });
                                });
                            </script>
                        </div>
                    </div>`;
        opciones+= "<label> Hasta: </label>";
        opciones+= `<div class="container">
                        <div class="row">
                            <div class='col-sm-6'>
                                <div class="form-group">
                                    <div class='input-group date' id='datetimepicker2'>
                                        <input type='text' class="form-control" id='operacionesHasta' />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $('#datetimepicker2').datetimepicker({
                                        format: 'YYYY-MM-DD'
                                    });
                                });
                            </script>
                        </div>
                    </div>`;
        opciones += "<button class='btn btn-primary' onclick='buscarOperaciones()'> Buscar </button>";
        $("#info").html(opciones);
        $("#opciones").ready(function(){
            var empleados = data.empleados;
            var ids = empleados.map(function(empleado){
                return empleado.id;
            });
            console.log(ids);
            cargarSelect('#selectOperaciones', ids);
        });
    }
}

function buscarOperaciones(){
    var idEmpleado = $("#selectOperaciones").val();
    var fechaDesde= "/" + $("#operacionesDesde").val();
    var fechaHasta = $("#operacionesHasta").val();
    console.log(fechaHasta);
    if(fechaHasta){
         fechaHasta = "/" + $("#operacionesHasta").val();
    }
    else{
         fechaHasta ="";
    }

    $.ajax({
        url:'empleados/operaciones/'+idEmpleado + fechaDesde + fechaHasta,
        headers: { token : localStorage.getItem('token')},
        type:"GET",
        dataType:"json"
    }).then(tablaOperaciones, error);
}

function tablaOperaciones(data, status, xhr){
    console.log(data);
}