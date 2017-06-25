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
        var nombre = $("#nombreNuevo").val();
        var apellido = $("#apellidoNuevo").val();
        var usuario = $("#usuarioNuevo").val();
        var clave = $("#clave").val();
        $.ajax({
            url:"empleados",
            type:"POST",
            dataType:'json',
            data: {usuario: empleado, pass: clave}
        }).then(registrado,error);
    })
}



function traerEmpleados(){
    
    $.ajax({
        url:'empleados/lista',
        headers: { token : localStorage.getItem('token')},
        type: "GET",
        dataType: 'json'
        
    }).then(empleados, error)
}

function empleados(data){
    if(data.exito){
        var tabla = "<table class=' table table-striped'> <thead> <tr> <td> Id </td> <td> Usuario </td> <td> Activo </td> <td> Borrar / Modificar </td> </tr> </thead>";
        tabla += "<tbody>";
        for (var element in data.empleados) {
            var empleado = data.empleados[element];
            if(empleado.activo){
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

    }
    else{
        $("#info").html("<h2> Usted no tiene los permisos para lo requerido </h2>");
    }
}
function borrarEmpleado(idEmpleado){
    $.ajax({
        url: 'empleados',
        type:"DELETE",
        dataType: 'json',
        data : {id: idEmpleado }
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

    
    $("#register").attr('id','modificar');

    $("#idEmpleado").removeClass("hidden");
    $('#agregarEmpleado').modal('show');
    $("#modificar").click(function(){
        var modificado = $("#usuarioNuevo").val();
        var passModificado = $("#clave").val();
        
        $.ajax({
            url:'empleados',
            type:'PUT',
            dataType:'json',
            data: {id: empleado.id, usuario: modificado, pass :passModificado }
        }).then(modificado, error);
        
    })
}

function modificado(data){
    console.info(data);
    if(data.exito){
        alert("modificado");
    }
}

function logueosEmpleados(){
    $.ajax({
        url: 'empleados/logueos',
        headers: { token : localStorage.getItem('token')},
        type: "GET",
    }).then(tablaLogueos, error);
}

function tablaLogueos(data){
    console.log(data);
}



function actualizarEmpleado(idEmpleado){
    
    $.ajax({
        url:'empleados',
        type:"PATCH",
        dataType: 'json',
        data: { id: idEmpleado}
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
    })
}