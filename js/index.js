$(document).ready(function(){
    loguear();
    var colores = ['rojo', 'blanco', 'negro', 'azul', 'verde'];
    var marcas = ['renault', 'ford', 'chevrolet', 'peugeot'];
    cargarSelect("#color", colores);
    cargarSelect("#marca", marcas);
    $('#agregarEmpleado').on('hidden.bs.modal', function (e) {
        $("[name=modalEmpleado]").prop('onclick',null).off('click');

        $("#modificar").attr('id', 'register');
        $("#idEmpleado").addClass("hidden");
        $("#usuarioNuevo").val("");
        $("#clave").val("");
    })
    eventEstacionar();
    salidaAuto();
});



/*
window.onbeforeunload = function(e){
        $.ajax({
        url:"desloguear",
        type:"POST",
    }).then(refresh, error)
}
*/
function cargarSelect(id, elementos){
    $.each(elementos, function(key, value) {   
        $(id)
            .append($("<option></option>")
                        .attr("value",value)
                        .text(value)); 
    });
}

                                            /* ESTACIONAMIENTO */
function tablaLugares(lugares){
        var tabla = "<table class=' table table-striped'> <thead> <tr> <td> Numero </td> <td> Piso </td> <td> Patente </td> <td> Reservado </td> <td> Operar</td> </tr> </thead>";
        tabla += "<tbody>";
        for (var element in lugares) {
            var lugar = lugares[element];
            var button = "";
            if(lugar.patente == null){
                lugar.patente = "";
                tabla += "<tr class='success'>";
                button = buttonEstacionar(lugar.numero,true);
            }
            else{
                tabla += "<tr class='danger'>";
                button = buttonEstacionar(lugar.numero);
            }
            if(lugar.reservado){
                lugar.reservado = "Reservado";
            }
            else{
                lugar.reservado = "No";
            }
            tabla+= "<td>" + lugar.numero + "</td> <td>" + lugar.piso + "</td> <td>" + lugar.patente + "</td> <td>" + lugar.reservado +"</td> <td>" + button +"</td> </tr>";
        }
        tabla += "</tbody> </table>";
        return tabla;
}

function buttonEstacionar(numero, estacionar=false){
    var button = "<button class='btn";
    if(estacionar){
        button += " btn-success' onclick='modalEstacionar("+ numero +")'> estacionar </button>";
    }
    else{
        button += " btn-danger' onclick='sacar("+ numero + ")'> sacar </button> ";
    }
    return button;
}

function modalEstacionar(numero){
    $("#lugar").val(numero);
    $('#ingresoAuto').modal('show');

}
function sacar(informacion){
    var datos ="";
    if(isNaN(informacion)){
        datos = {'patente': informacion}
    }
    else{
        datos = {'lugar': informacion }
    }
    $.ajax({
        url:'estacionamiento',
        type:'DELETE',
        dataType: 'json',
        processData: false,
        data: datos
    }).then(sacado, error)   
}
function sacado(data){
    if(data.exito){
        estacionamiento();
        var sacado  = "<h3 class='text-success text-center'> Salida </h3>"
        sacado += "<p> <b> Patente </b> " + data.auto[0].patente + "</p>";
        sacado += "<p> <b> Color </b> " + data.auto[0].color + "</p>";
        sacado += "<p> <b> Marca </b> " + data.auto[0].marca + "</p>";
        sacado += "<h4 class='text-success text-center'> Precio: " + data.precio + "</h4>"; 
    }
    $("#respuesta").html(sacado);
}
function eventEstacionar(){
    $("#estacionar").click(function(){
        var lugarAuto = $("#lugar").val();
        var patenteAuto = $("#patenteAuto").val();
        var colorAuto = $("#color").val();
        var marcaAuto = $("#marca").val();
        $.ajax({
            url:'estacionamiento',
            type:'POST',
            dataType: 'json',
            data: {lugar: lugarAuto, patente: patenteAuto, color: colorAuto, marca: marcaAuto}
        }).then(estacionado, error);
    })
}

function modalSalida(){
    $("#modalSalida").modal('show');
}

function salidaAuto(){
    $("#salida").click(function(){
        var patente = ("#patenteSalida").val();
        sacar(patente);
    })
}
function estacionado(data){
    alert("estacionado");
    estacionamiento();
}

function estacionamiento(){
    $.ajax({
        url:'estacionamiento',
        type:'GET',
        dataType:'json',
    }).then(estacionamientOk,error)
}

function estacionamientOk(data){
    if(data.exito){
        var tabla =tablaLugares(data.lugares);
        $("#info").html(tabla);
        var precio = "<h3 class='text-success text-center'> Precios </h3>";
        precio+= "<p> <b> Hora: </b>" + data.precios.precioHora + "</p>";
        precio+= "<p> <b> Media Estadia: </b>" + data.precios.precioMedia + "</p>";
        precio+= "<p> <b> Estadia: </b>" + data.precios.precioEstadia + "</p>";
        $("#precios").html(precio);
    }

}

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

function logueado(data){
    if(data.exito){
        var htmlLogueado = '<h4 class="navbar-text"> Bienvenido  ' +  data.empleado + ' </h4>';
        htmlLogueado += '  <button class="btn btn-default navbar-btn" type="button" id="desLogin"> Salir </button>';
        
        estacionamiento();
        $("#log").html(htmlLogueado);
        $("#log").attr('id','logout');
        if(data.empleado == 'admin'){
            $("#empleados").removeClass("hidden");
        }
        $("#autos").removeClass("hidden");
        $("#operaciones").removeClass("hidden");
        desloguear();
    }

}



function deslogueado(data){
    if(data.exito){
        var htmlDeslogueado =' <form class="navbar-form navbar-right" >';
        htmlDeslogueado +='<div class="form-group">';
        htmlDeslogueado +=  '<input type="text" class="form-control" placeholder="usuario" name="usuario" id="usuario"> </div>';
        htmlDeslogueado +='<div class="form-group">';
        htmlDeslogueado +=  '<input type="password" class="form-control" placeholder="password" name="pass" id="pass"> </div>';
        htmlDeslogueado +='<button class="btn btn-default" type="button" id="login"><span class="glyphicon glyphicon-log-in"></span> Login </button> </form>'
        $("#logout").html(htmlDeslogueado);
        $("#logout").attr('id', 'log');
        $("#empleados").addClass('hidden');
        $("#autos").addClass("hidden");
        $("#operaciones").addClass("hidden");
        $("#info").html("");
        $("#precios").html("");
        $("#respuesta").html("");
        loguear();
    }
    else{
        console.info(data)
    }
}

function error(data) {
    alert("error");
    console.info(data);
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
