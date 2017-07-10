function listado(){
    var listadoAutos = "<h3> Autos estacionados </h3>";
    listadoAutos += `<div class="form-group">
                        <label for="usr">Patente:</label>
                        <input type="text" class="form-control" id="patente">
                    </div>`;
    listadoAutos += traerDate("autosDesde") + traerDate("autosHasta");
    listadoAutos += "<button class='btn btn-primary' onclick='buscarAuto()'> Buscar </button>";
    listadoAutos += "<button class='btn btn-primary' onclick='buscarTodos()'> Todos </button>";
    $("#info").html(listadoAutos);
}

function buscarAuto(){
    $("#usados").remove();
    var patente = $("#patente").val();
    var fechaDesde = "/" + $('#autosDesdeInput').val();
    var fechaHasta = $('#autosHastaInput').val();
    if(fechaHasta){
        fechaHasta = "/" + fechaHasta;
    }
    else{
        fechaHasta = "";
    }
    $.ajax({
        url: 'estacionamiento/buscar/'+ patente + fechaDesde + fechaHasta,
        headers: { token : localStorage.getItem('token')},
        dataType:"json"
    }).then(tablaAutos, errores);
}

function buscarTodos(){
    var fechaDesde = "/" + $('#autosDesdeInput').val();
    var fechaHasta = $('#autosHastaInput').val();
    if(fechaHasta){
        fechaHasta = "/" + fechaHasta;
    }
    else{
        fechaHasta = "";
    }
    $.ajax({
        url: 'estacionamiento/buscarTodos' + fechaDesde + fechaHasta,
        headers: { token : localStorage.getItem('token')},
        dataType:"json"
    }).then(tablaTodos, errores);
}

function tablaAutos(data, status, xhr){
    if(xhr.status == 200){
        var tabla = `<div class="row" id="usados">
                        <ul class="text-center list-inline">
                            <li>${data.patente}</li>
                            <li>${data.color}</li>
                            <li>${data.marca}</li>
                        </ul>
                        <table class=" table table-striped">
                            <thead>
                                <tr>
                                    <td> Cochera </td>
                                    <td> Entrada </td>
                                    <td> Salida </td>
                                    <td> Precio </td>
                                </tr>
                            </thead>
                            </tbody>`;
        for (var element in data.operaciones) {
            var operacion = data.operaciones[element];
            tabla +=`<tr>
                        <td> ${operacion.cochera} </td>
                        <td> ${operacion.entrada} </td>
                        <td> ${operacion.salida} </td>
                        <td> ${operacion.precio} </td>
                        </tr>`;
            
        }
        tabla += "</tbody> </table> </div>";
        $("#principal").append(tabla);
    }
    else if(xhr.status == 206){
        error206(data);
    }
}

function tablaTodos(data, status, xhr){
    if(xhr.status == 200){
        var tabla = '<div class="row" id="usados">';
        for (var key in data) {
            var datos = data[key];
            console.log(datos);
            tabla +=`<ul class="text-center list-inline">
                        <li>${datos.patente}</li>
                        <li>${datos.color}</li>
                        <li>${datos.marca}</li>
                    </ul>
                    <table class=" table table-striped">
                        <thead>
                            <tr>
                                <td> Cochera </td>
                                <td> Entrada </td>
                                <td> Salida </td>
                                <td> Precio </td>
                            </tr>
                        </thead>
                        </tbody>`;
        
            for (var element in datos.operaciones) {
                var operacion = datos.operaciones[element];

                tabla +=`<tr>
                            <td> ${operacion.cochera} </td>
                            <td> ${operacion.entrada} </td>
                            <td> ${operacion.salida} </td>
                            <td> ${operacion.precio} </td>
                            </tr>`;
                
            }
        tabla += "</tbody> </table>";
        }
        tabla += "</div>";
        $("#principal").append(tabla);
    }
    else if(xhr.status == 206){
        error206(data);
    }
}