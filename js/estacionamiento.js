                                            /* ESTACIONAMIENTO */
function tablaLugares(lugares){
        var tabla = "<table class=' table table-striped'> <thead> <tr> <td> Numero </td> <td> Piso </td> <td> Patente </td> <td> Reservado </td> <td> Operar</td> </tr> </thead>";
        tabla += "<tbody>";
        for (var element in lugares) {
            var lugar = lugares[element];
            var button = "";
            if(lugar.patente == null){
                lugar.patente = "";
                tabla += "<tr class=''>";
                button = buttonEstacionar(lugar.numero,true);
            }
            else{
                tabla += "<tr class=''>";
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

function buttonEstacionar(numero, estacionar){
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
    if(numero){
        $("#lugar").val(numero);
    }
    else{
        $("#lugar").removeAttr("disabled");
    }

    $('#ingresoAuto').modal('show');    
        

}
function sacar(informacion){
    
    $.ajax({
        url:'estacionamiento/sacar/'+ informacion,
        headers: { token : localStorage.getItem('token')},
        type:'DELETE',
        dataType: 'json',        
    }).then(sacado, error)   
}
function sacado(data, status, xhr){
    if(xhr.status == 200){
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
            url:'estacionamiento/estacionar',
            headers: { token : localStorage.getItem('token')},
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
        var patente = $("#patenteSalida").val();
        sacar(patente);
    })
}
function estacionado(data){
    alert("estacionado");
    estacionamiento();
}

function estacionamiento(){
    $.ajax({
        url:'estacionamiento/lugares',
        headers: { token : localStorage.getItem('token')},
        type:'GET',
        dataType:'json',
    }).then(estacionamientOk,error)
}

function estacionamientOk(data, status, xhr){
    if(xhr.status == 200){
        var tabla =tablaLugares(data.lugares);
        $("#info").html(tabla);
        var precio = "<h3 class='text-success text-center'> Precios </h3>";
        precio+= "<p> <b> Hora: </b>" + data.precios.precioHora + "</p>";
        precio+= "<p> <b> Media Estadia: </b>" + data.precios.precioMedia + "</p>";
        precio+= "<p> <b> Estadia: </b>" + data.precios.precioEstadia + "</p>";
        $("#precios").html(precio);
    }

}

function modalEstacionarOnClose(){
    $('#ingresoAuto').on('hidden.bs.modal', function (e) {
        if(!$("#lugar").is(":disabled")){
            $("#lugar").attr("disabled", true);
        }
        $("#lugar").val("");
    })
}