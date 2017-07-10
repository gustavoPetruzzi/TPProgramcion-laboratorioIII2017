function masUsada(){
    var desde = "<h3> Cocheras Mas usadas </h3> "
    desde += traerDate("masDesde") + traerDate("masHasta");
    desde += "<button class='btn btn-primary' onclick='buscarMas()'> Buscar </button>";


    $("#info").html(desde);
}

function buscarMas(){
    $("#usados").remove();
    var fechaDesde =  "/" + $('#masDesdeInput').val();
    var fechaHasta = $('#masHastaInput').val();
    if(fechaHasta){
        fechaHasta = "/" + fechaHasta;
    }
    else{
        fechaHasta = "";
    }
    $.ajax({
        url:'cocheras/mas' + fechaDesde + fechaHasta,
        headers: { token : localStorage.getItem('token')},
        dataType:"json"
    }).then(tablaUsadas, errores);
}

function menosUsada(){
    var desde = "<h3> Cocheras menos usadas </h3> "
    desde += traerDate("menosDesde") + traerDate("menosHasta");
    desde += "<button class='btn btn-primary' onclick='buscarMenos()'> Buscar </button>";


    $("#info").html(desde);
}

function buscarMenos(){
    $("#usados").remove();
    var fechaDesde = "/" + $('#menosDesdeInput').val();
    var fechaHasta = $('#menosHastaInput').val();
    if(fechaHasta){
        fechaHasta = "/" + fechaHasta;
    }
    else{
        fechaHasta = "";
    }
    $.ajax({
        url:'cocheras/menos' + fechaDesde + fechaHasta,
        headers: { token : localStorage.getItem('token')},
        dataType:"json"
    }).then(tablaUsadas, errores);
}

function nunca(){
    
    var desde = "<h3> Cocheras Nunca usadas </h3> "
    desde += traerDate("menosDesde") + traerDate("menosHasta");
    desde += "<button class='btn btn-primary' onclick='buscarNunca()'> Buscar </button>";

    $("#info").html(desde);
}
function buscarNunca(){
    $("#usados").remove();
    var fechaDesde = "/" + $('#menosDesdeInput').val();
    var fechaHasta = $('#menosHastaInput').val();
    if(fechaHasta){
        fechaHasta = "/" + fechaHasta;
    }
    else{
        fechaHasta = "";
    }
    $.ajax({
        url:'cocheras/nunca' + fechaDesde + fechaHasta,
        headers: { token : localStorage.getItem('token')},
        dataType:"json"
    }).then(tablaUsadas, errores);
}

function tablaUsadas(data,status, xhr){
    if(xhr.status == 200){
        var tabla = `<div class="row" id="usados">
                        <table class=" table table-striped"> 
                            <thead>
                                <tr>
                                    <td> Lugar </td>
                                    <td> Piso </td>
                                    <td> Patente </td>
                                    <td> Reservado </td>
                                    <td> Cantidad </td>
                                <tr>
                            </thead>
                            <tbody>`;
        for (var element in data) {
            
            var lugar = data[element];
            if(lugar.patente == null){
                lugar.patente = "";
                tabla += "<tr class=''>";
            }
            else{
                tabla += "<tr class=''>";
            }
            if(lugar.reservado == "1"){
                lugar.reservado = "Reservado";
            }
            else{
                lugar.reservado = "No";
            }
            tabla+= "<td>" + lugar.numero + "</td> <td>" + lugar.piso + "</td> <td>" + lugar.patente + "</td> <td>" + lugar.reservado +"</td> <td>" + lugar.cantidad +"</td> </tr> </div>";
        }
        tabla += "</tbody> </table>";
        $("#principal").append(tabla);
    }
    else if(xhr.status == 206){
        var error =`<div class='row' id='usados'>
                        <h3> ${data}</h3>
                    </div>`;
        $("#principal").append(error);

    }

}