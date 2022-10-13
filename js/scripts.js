//activar listener del boton de baja de todas las personas
function confirmarBaja() {
	if (window.confirm("¿Estas seguro?")) {
		document.querySelector('#formularioBaja').submit();
	}
}
//activar listener de los botones de modificación de persona
var botonsModificar = document.querySelectorAll('.btn btn-primary modificar');
//recorrer array de los objetos boton y activar onclick. Se ejecuta funcion trasladarDAtos
botonsModificar.forEach(function (boto) {
	boto.onclick = traslladarDades;
});

//función para trasladar los datos de la fila seleccionada al formulario oculto
function traslladarDades() {
	/*
	<tr>
	  <td class='nif'>40000000A</td>
	  <td><input type='text' value='O-Ren Ishii' class='nombre'></td>
	  <td><input type='text' value='Graveyard avenue, 66' class='direccion'></td>
	  <td>
			<form method='post' action='#'>
				<input type='hidden' name='nifBaja' value='40000000A'>
				<button type="submit" class="btn btn-warning" name='bajaPersona'>Baja</button>
			</form>
			<button type="button" class="btn btn-primary modificar" name='modiPersona'>Modificar</button>
	  </td>
	</tr>
	*/

	//situarnos en la etiqueta tr que corresponda a la fila donde se encuentra el botón
	var tr = this.closest('tr') //buscar l'etiqueta tr de la fila a la que pertany el botó on l'usuari a fet click

	//recuperar los datos de la persona
	var nif = tr.querySelector('.nif').innerText;// 40000000A
	var nom = tr.querySelector('.nombre').value; // O-Ren Ishii
	var direccion = tr.querySelector('.direccion').value;

	//trasladar los datos al formulario oculto
	document.querySelector('[name=nifModi]').value = nif;
	document.querySelector('[name=nombreModi]').value = nombre;
	document.querySelector('[name=direccionModi]').value = direccion;

	//submit del formulario
	document.querySelector('#formularioModi').submit();
}
