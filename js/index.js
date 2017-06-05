$(document).ready(function(){
    loguear();
    registrarEmpleado();
});
function registrado(data){
    if(data.exito){
        alert("empleado Registrado");
    }
}
function registrarEmpleado(){
    $("#register").click(function(){
        var empleado = $("#usuarioNuevo").val();
        var clave = $("#clave").val();
        $.ajax({
            url:"empleados",
            type:"POST",
            dataType:'json',
            data: {usuario: empleado, pass: clave}
        }).then(registrado,error);
    })
}
window.onbeforeunload = function(e){
        $.ajax({
        url:"desloguear",
        type:"POST",
    }).then(refresh, error)
}


function traerEmpleados(){
    $.ajax({
        url:'empleados',
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
            tabla += "<td> <button type='button' class='btn btn-warning' onclick='modificarEmpleado(" + empleado.id + ")'><span class='glyphicons glyphicons-random'></span> Modificar</button>";
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
















//                                         Funciones de Logueo.
// TODO Checkear que hacer cuando se refresca la pagina
// TODO Ver que hacer cuando no se desloguea normalmente en  y queda un registro colgado(PHP)
function refresh(){
    alert("guardando refresh");
}
function logueado(data){
    
    if(data.exito){
        var htmlLogueado = '<h4 class="navbar-text"> Bienvenido  ' +  data.empleado + ' </h4>';
        htmlLogueado += '  <button class="btn btn-default navbar-btn" type="button" id="desLogin"> Salir </button>';
        $("#log").html(htmlLogueado);
        $("#log").attr('id','logout');
        $("#empleados").removeClass("hidden");
        desloguear();
    }

}



function deslogueado(data){
    
    if(data.exito){
        var htmlDeslogueado =' <form class="form-signin navbar-form" >';
        htmlDeslogueado +=  '<input type="text" class="form-control" placeholder="usuario" name="usuario" id="usuario">';
        htmlDeslogueado +=     '<input type="password" class="form-control" placeholder="password" name="pass" id="pass">';
        htmlDeslogueado +='<button class="btn btn-default" type="button" id="login"> Login </button> </form>'
        $("#logout").html(htmlDeslogueado);
        $("#logout").attr('id', 'log');
        $("#info").html("");
        $("#estado").html("");
        loguear();
    }
    else{
        console.info(data)
    }
}

function error(data) {
    alert("error");
    console.info(data);
    console.info(data.data);
}




function loguear(){
    $("#login").click(function(){
        var usuario = $("#usuario").val();
        var pass = $("#pass").val();
        $.ajax({
            url: "login",
            type:"POST",
            data: { usuario : usuario, pass: pass},

        }).then(logueado, error)
    })
}
function desloguear(){
    $("#desLogin").click(function(){ 
        $.ajax({
            url:"desloguear",
            type:"POST",
        }).then(deslogueado, error)
    })
}
