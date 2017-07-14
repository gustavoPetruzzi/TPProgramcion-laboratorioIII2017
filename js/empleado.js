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
        }).then(registrado,errores);
    })
}



function traerEmpleados(empleados){
    
    $.ajax({
        url:'empleados/lista',
        headers: { token : localStorage.getItem('token')},
        type: "GET",
        dataType: 'json'
        
    }).then(empleados, errores)
}

function tablaEmpleados(data){
    if(data.exito){
        var tabla = `<table class=' table table-striped' id='empleadosTable'>
                         <thead>
                            <tr>
                                <td> Id </td>
                                <td> Usuario </td>
                                <td> Activo </td>
                                <td> Borrar / Modificar </td>
                            </tr> 
                        </thead>
                        <tbody>`;
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
            $("#empleadosTable").DataTable({
                "paging": false,
                "ordering": false,
                
            });    
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
        
    }).then(borrado, errores)

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
        }).then(modificado, errores);
        
    })
}

function modificado(data, status, xhr){
    if(xhr.status == 200){
        alert("Modificado");
        traerEmpleados();
    }
}

function logueosEmpleados(){
    traerEmpleados(logueosDatos);
    
    /*                                      PARA DESCARGAR ARCHIVO.
    var local ='/TPProgramcion-laboratorioIII2017/';
    var urlDownload = window.location.protocol+ '//' +  window.location.host + local+'/empleados/logueos/reporte/' + localStorage.getItem('token') +'/23';
    var anchor = "<a href ='"+ urlDownload +"' download  id='excelEmpleados'>algo </a>";
    $("#info").append(anchor);
    
    $("#info").ready(function(){
        $("#excelEmpleados").click();
    });
    */    

}

function logueosDatos(data, status, xhr){
    if(xhr.status == 200){
        
        var opciones= "<div class='form-group'>";
        opciones+= "    <label for='selectLogueos'> id </label>";
        opciones+= "    <select class='form-control' id='selectLogueos'> </select> </div>";
        opciones+= traerDate("logueosDesde") + traerDate("logueosHasta");
        opciones+= "<button class='btn btn-primary' onclick='buscarLogueos()'> Buscar </button>";
        

        $("#info").html(opciones);
        $("#opciones").ready(function(){
            var empleados = data.empleados;
            var ids = empleados.map(function(empleado){
                return empleado.id;
            });
            cargarSelect('#selectLogueos', ids);
        });
    }
}

function buscarLogueos(){
    $("#usados").remove();
    var idEmpleado = $("#selectLogueos").val();
    var fechaDesde = "/" + $("#logueosDesdeInput").val();
    var fechaHasta = $("#logueosHastaInput").val();
    if(fechaHasta){
        fechaHasta  ="/" + fechaHasta;
    }
    else{
        fechaHasta ="";
    }
    sessionStorage.setItem('link', idEmpleado+fechaDesde+fechaHasta);
    $.ajax({
        url: 'empleados/logueos/' + idEmpleado + fechaDesde + fechaHasta,
        headers: {token : localStorage.getItem('token')},
        dataType:'json',
        success:tablaLogueos,
        error: errores
    });
}
    


function tablaLogueos(data, status, xhr, link){
    
    if(xhr.status == 200){
        var local = "/TPProgramcion-laboratorioIII2017/";
        var urlDownload = window.location.protocol+ '//' +  window.location.host + local+'reportes/logueos/' + localStorage.getItem('token') +"/" + sessionStorage.getItem('link');

        var tabla = `<div class="row" id="usados">`;
        tabla += "<table class=' table table-striped' id='logueosTable'> <thead> <tr> <td> Dia </td> <td> Entrada </td> <td> Salida </td> </tr> </thead>";
        tabla += "<tbody>";
            for (var element in data.operaciones) {
                var operacion = data.operaciones[element];
                tabla += "<tr> <td>" + operacion.dia + "</td> <td>" + operacion.entrada + "</td> <td>" + operacion.salida + "</td> </tr>";
            }
        tabla+= "</tbody> </table> ";
        tabla+= "<a class='btn' href='"+ urlDownload +"' download>Exportar</a> </div>";
        $("#info").append(tabla);
        $("#info").ready(function(){
            $("#logueosTable").DataTable();    
        });
    }
    else if(xhr.status == 206){
        error206(data);
    }
    

}


function actualizarEmpleado(idEmpleado){
    
    $.ajax({
        url:'empleados/actualizar/'+ idEmpleado,
        headers: { token : localStorage.getItem('token')},
        type:"PATCH",
        dataType: 'json',
        
    }).then(actualizado,errores);
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
            cargarSelect('#selectOperaciones', ids);
        });
    }
}

function buscarOperaciones(){
    $("#usados").remove();
    var idEmpleado = $("#selectOperaciones").val();
    var fechaDesde= "/" + $("#operacionesDesde").val();
    var fechaHasta = $("#operacionesHasta").val();
    
    if(fechaHasta){
         fechaHasta = "/" + $("#operacionesHasta").val();
    }
    else{
         fechaHasta ="";
    }
    sessionStorage.setItem('link', idEmpleado+fechaDesde+fechaHasta);
    $.ajax({
        url:'empleados/operaciones/'+idEmpleado + fechaDesde + fechaHasta,
        headers: { token : localStorage.getItem('token')},
        type:"GET",
        dataType:"json"
    }).then(tablaOperaciones, errores);
}

function tablaOperaciones(data, status, xhr){
    if(xhr.status == 200){
        var datos = `<div class="row" id="usados">
                        <dl class="dl-horizontal text-center">
                            <dt> id </dt>
                            <dd> ${data.id} </dd>
                            <dt> Nombre </dt>
                            <dd> ${data.nombre} </dt>
                            <dt> Apellido </dt>
                            <dd> ${data.apellido} </dt>
                            <dt> Activo </dt>
                            <dd> ${data.activo} </dt>
                        </dl>
                    `;
        datos += ` <table class= " table table-striped">
                        <thead>
                            <tr>
                                <td> Entrada </td>
                                <td> Salida </td>
                                <td> Precio </td>
                            </tr>
                        </thead>
                        <tbody>`;
        var local = "";
        var urlDownload = window.location.protocol+ '//' +  window.location.host + local+'reportes/operaciones/' + localStorage.getItem('token') +"/" + sessionStorage.getItem('link');

        for (var key in data.operaciones) {
                var operacion = data.operaciones[key];
                datos +=`<tr>
                            <td> ${operacion.entrada} </td>
                            <td> ${operacion.salida} </td>
                            <td> ${operacion.precio} </td>
                         </tr>`;
            }
        datos += "</tbody> </table> ";
        datos+= "<a class='btn' href='"+ urlDownload +"' download>Exportar</a> </div>";
        $("#principal").append(datos);
    }
    else if(xhr.status == 206) {
        error206(data);
    }
    

}