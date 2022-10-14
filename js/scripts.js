//activar listener del boton de baja de todas las personas
function confirmarBaja() {
	if (window.confirm("¿Estas seguro?")) {
		document.querySelector('#formularioBaja').submit();
	}
}
//activar listener de los botones de modificación de persona
var botonsModificar = document.querySelectorAll('.modificar');

//recorrer array de los objetos boton y activar onclick. Se ejecuta funcion trasladarDAtos
botonsModificar.forEach(function (boto) {
	boto.onclick = traslladarDades;
});

//función para trasladar los datos de la fila seleccionada al formulario oculto
function traslladarDades() {

	//situarnos en la etiqueta tr que corresponda a la fila donde se encuentra el botón
	var tr = this.closest('tr') //buscar l'etiqueta tr de la fila a la que pertany el botó on l'usuari a fet click

	//recuperar los datos de la persona
	var nif = tr.querySelector('.nif').innerText;
	var nombre = tr.querySelector('.nombre').value;
	var direccion = tr.querySelector('.direccion').value;

	//trasladar los datos al formulario oculto
	document.querySelector('[name=nifModi]').value = nif;
	document.querySelector('[name=nombreModi]').value = nombre;
	document.querySelector('[name=direccionModi]').value = direccion;

	//submit del formulario
	document.querySelector('#formularioModi').submit();
}
