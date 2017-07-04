function masUsada(){
    var desde = traerDate("masDesde") + traerDate("masHasta");
     desde += "<button class='btn btn-primary' onclick='buscarMas()'> Buscar </button>";


    $("#info").html(desde);
    /*
    $.ajax({
        url:'cocheras/mas/2017-01-10/2017-02-12',
        headers: { token : localStorage.getItem('token')},
        dataType:"json"
    }).then(tablaUsadas);
    */
}

function buscarMas(){
    $.ajax({
        url:'cocheras/mas/2017-01-10/2017-02-12',
        headers: { token : localStorage.getItem('token')},
        dataType:"json"
    }).then(tablaUsadas);
}


function menosUsada(){
    $.ajax({
        url:'cocheras/menos/2017-01-10/2017-02-12',
        headers: { token : localStorage.getItem('token')},
        dataType:"json"
    }).then(tablaUsadas);
}


function nunca(){
    $.ajax({
        url:'cocheras/nunca/2017-01-10/2017-02-12',
        headers: { token : localStorage.getItem('token')},
        dataType:"json",
    }).then(tablaUsadas);
}

function tablaUsadas(data){
    var date = dateTimePicker("algo");
    console.log(data)
}