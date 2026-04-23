function validarFormulario() {

var telefono = document.forms[0]["telefono"].value;
var fechaInicio = document.forms[0]["fecha_entrada"].value;
var fechaFin = document.forms[0]["fecha_salida"].value;

for (var i = 0; i < telefono.length; i++) {

var caracter = telefono[i];

if (fechaInicio > fechaFin) {
alert("Error: la fecha de inicio no puede ser posterior a la fecha de fin");
return false;

}

if (caracter < '0' || caracter > '9') {
alert("Error: el teléfono solo debe contener números");
return false;
}

}

return true;

}



