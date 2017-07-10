function admin(){
    $("#usuario").val("admin");
    $("#pass").val("admin");
    $("#login").trigger('click');
}

function user(){
    $("#usuario").val("casto");
    $("#pass").val("1111");
    $("#login").trigger('click');
}

function pruebaEstacionar(){
    $("#lugar").val(10);
    $("#patenteAuto").val("BUD111")
    $("#color").val("verde");
    $("#marca").val("peugeot");
    $("#estacionar").trigger('click');
}
function pruebaSalida(){
    sacar(10);
}

function registrarEmpleadoPrueba(){
    
    $("#nombreNuevo").val("prueba");
    $("#apellidoNuevo").val("pruebetti");
    $("#usuarioNuevo").val("usuariop");
    $("#clave").val("11111");
    // HACERLO ADMIN
    //$('#adminNuevo').prop('checked', true);
    registrarEmpleado();
    $("#register").trigger('click');
}
function pruebaModificar(){
    var obj = { "id":8, "nombre":"juan", "usuario":"algo", "apellido": "otra", "_pass":"1111", "admin":"1"};
    modificarEmpleado(JSON.stringify(obj));
    $("#idEmpleado").val(8);
    $("#nombreNuevo").val("carlosModificado");
    $("#apellidoNuevo").val("algoModificado");
    $("#usuarioNuevo").val("juanModificado");
    $("#clave").val("12345");
    $("#modificar").trigger('click');
        
}