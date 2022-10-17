<?php

//activar variables de sesión
session_start();

//inicialización de variables
$nif = null;
$nombre = null;
$direccion = null;
$mensajes = null;
const CLAVE = 'zxspectrum';

//array para guardar las personas
$arrayPersonas = [];

//si existe la variable de sesión substituyo el contenido del array
if (isset($_SESSION['personas'])) {
	$arrayPersonas = $_SESSION['personas'];
} else {
	$_SESSION['personas'] = [];
}

//ALTA DE PERSONA
if (isset($_POST['alta'])) {
	//validar datos obligatorios y recuperar los datos sin espacios en blanco -trim()-
	try {
		//recogida del nif. Si esta en blanco lanza excepcion y si es correcto el formato se aplica el else que comprueba si existe
		if (!$nif = trim(filter_input(INPUT_POST, 'nif'))) {
			throw new Exception('El campo nif no debe estar vacio.', 1);
		}
		//validar que el nif no exista en la base de datos
		else {
			$existe = array_key_exists($nif, $arrayPersonas);
			if ($existe) {
				throw new Exception('El NIF proporcionado ya existe en la base de datos.', 2);
			}
		}
		//recogida del nombre. Si esta en blanco lanza excepcion y si no pasa al else que guarda el nombre previamente formateado a minusculas en el array
		if (!$nombre = trim(filter_input(INPUT_POST, 'nombre'))) {
			throw new Exception('El campo nombre no debe estar vacio.', 3);
		} else {
			//guardamos el nombre y dirección en minúsculas con la primera letra en mayúsculas
			$nombre = ucfirst(strtolower($nombre));
		}
		//recogida de direccion. Si esta vacio lanza excepcion y si no la guarda en el array en minusculas
		if (!$direccion = trim(filter_input(INPUT_POST, 'direccion'))) {
			throw new Exception('El campo direccion no debe estar vacio.', 4);
		} else {
			//guardamos el nombre y dirección en minúsculas con la primera letra en mayúsculas
			$direccion = ucfirst(strtolower($direccion));
		}
		//si los tres campos son correctos lanza mensaje de alta efectuada
		if ($nif && $nombre && $direccion) {
			//guardar el nombre de la persona en el array.
			$arrayPersonas[$nif]['nombre'] = $nombre;
			//guardar la direccion de la persona en el array
			$arrayPersonas[$nif]['direccion'] = $direccion;
			$mensajes = 'Alta efectuada correctamente.';
			//limpiar el formulario. Vuelvo a asignar a las variables que recogen los inputs
			$nif = null;
			$nombre = null;
			$direccion = null;
		}
	} catch (Exception $e) {
		$mensajes = $e->getCode() . '. ' . $e->getMessage();
	}
}

//BAJA DE TODAS LAS PERSONAS
//detectar cuando el usuario pulsa el boton baja
if (isset($_POST['baja'])) {
	//inicializar el array. En esta opcion se crea el array de nuevo sobreescribiendolo
	$arrayPersonas = [];
	// En esta opcion se elimina cada elemento, pero deja el mismo array intacto
	foreach ($arrayPersonas as $i => $value) {
		unset($arrayPersonas[$i]);
	}
	$mensajes = 'Todas las personas han sido borradas de la base de datos.';
}

//BAJA DE LA PERSONA SELECCIONADA EN LA TABLA
//detectar cuando el usuario ha pulsado el boton de baja individual
if (isset($_POST['bajaPersona'])) {
	try {
		//recuperamos el nif y validamos que llegue informado. ESTE CAMPO DE NIF NO ES EL QUE ESTA VISIBLE EN LA TABLA SINO EL QUE ESTA OCULTO
		if (!$nifBaja = filter_input(INPUT_POST, 'nifBaja')) {
			throw new Exception('El campo nif no puede estar vacio.', 5);
		}
		//Desciframos el nif
		$nifBaja = $nifBaja ^ CLAVE;
		//validar que el nif exista en la base de datos. Si no existe lanza una excepcion, si existe la borra del array. ESTE CAMPO DE NIF NO ES EL QUE ESTA VISIBLE EN LA TABLA SINO EL QUE ESTA OCULTO
		$existe = array_key_exists($nifBaja, $arrayPersonas);
		if (!$existe) {
			throw new Exception('El Nif introducido no figura en la base de datos.', 6);
		}
		//borrar la fila del array 
		else {
			unset($arrayPersonas[$nifBaja]);
			//mostrar mensaje de baja efectuada
			$mensajes = 'Baja efectuada correctamente.';
		}
	} catch (Exception $e) {
		$mensajes = $e->getCode() . '. ' . $e->getMessage();
	}
}

//MODIFICACION DE LA PERSONA SELECCIONADA
//se detecta el input oculto del formulario que se rellena conjavascript al pulsar modificar 
if (isset($_POST['modificar'])) {
	global $mensajes;
	global $arrayPersonas;
	try {
		//recuperar nif sin espacios en blanco -trim() y validar datos. Si esta en blanco lanza excepcion.
		if (!$nifModi = trim(filter_input(INPUT_POST, 'nifModi'))) {
			throw new Exception('El campo Nif no debe estar vacio.', 7);
		}
		//Desciframos el nif
		$nifModi = $nifModi ^ CLAVE;
		//validar que el nif exista en la base de datos. Si se ha modificado y no es correcto lanza excepcion
		$existe = array_key_exists($nifModi, $arrayPersonas);
		if (!$existe) {
			throw new Exception("La modificacion del nif no es correcta ya que no figura en la base de datos.", 8);
		}
		//recuperar nombre sin espacios en blanco -trim() y validar datos. Si esta en blanco lanza excepcion.
		if (!$nombreModi = trim(filter_input(INPUT_POST, 'nombreModi'))) {
			throw new Exception('El campo nombre no debe estar vacio', 9);
		} else {
			//guardamos el nuevo nombre en minúsculas con la primera letra en mayúsculas
			$nombreModi = ucfirst(strtolower($nombreModi));
		}
		//recuperar direccion sin espacios en blanco -trim() y validar datos. Si esta en blanco lanza excepcion.
		if (!$direccionModi = trim(filter_input(INPUT_POST, 'direccionModi'))) {
			throw new Exception('El campo direccion no debe estar vacio', 10);
		} else {
			//guardamos dirección en minúsculas con la primera letra en mayúsculas
			$direccionModi = ucfirst(strtolower($direccionModi));
		}
		//sobreescribimos la persona en el array. Añadimos los nuevos datos al array en la misma fila del dni
		$arrayPersonas[$nifModi]['nombre'] = $nombreModi;
		$arrayPersonas[$nifModi]['direccion'] = $direccionModi;
		//mensaje de modificación efectuada
		$mensajes = 'Modificacion efectuada.';
	} catch (Exception $e) {
		$mensajes = $e->getCode() . '. ' . $e->getMessage();
	}
}

//la creacion de la tabla html la he puesto en una funcion que recorre el array y crea las columnas. Desde el HTML se realiza la llamada
function crearTablaPersonas($arrayPersonas)
{
	//ordenar el array que tenemos de menor a mayor
	ksort($arrayPersonas);
	//recorrer el array 
	foreach ($arrayPersonas as $numNif => $valores) {
		//variables con los valores. Son para simplificar la insercion en los strings de los inputs (demasiadas comillas)
		$valor1 = $arrayPersonas[$numNif]['nombre'];
		$valor2 = $arrayPersonas[$numNif]['direccion'];
		//cifrado del numero de nif en la baja
		$nifCifrado = (string)$numNif ^ CLAVE;
		//añadir las columnas con el contenido del array personas
		//se modifica la primera td y el input oculto para incluir el nif cifrado
		echo "<tr>
  <td class='nif' data-nif='$nifCifrado'>$numNif</td>
  <td><input type='text' value='$valor1'class='nombre'></td>
  <td><input type='text' value='$valor2' class='direccion'></td>
  <td>
	  <form method='post' action='#'>
		  <input type='hidden' name='nifBaja' value='$nifCifrado'>
		  <button type='submit' class='btn btn-warning' name='bajaPersona'>Baja</button>
	  </form>
	  <button type='button' class='btn btn-primary modificar'>Modificar</button>
  </td>
</tr>";
	}
}

//volcar el contenido del array en la variable de sesión
$_SESSION['personas'] = $arrayPersonas;

?>

<html>

<head>
	<title>PLA03</title>
	<meta charset='UTF-8'>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>

<body>
	<main>
		<h1 class='centrar'>PLA03: MANTENIMIENTO PERSONAS</h1>
		<br>
		<form method='post' action='#'>
			<div class="row mb-3">
				<label for="nif" class="col-sm-2 col-form-label">Nif</label>
				<div class="col-sm-10">
					<!--mantener el nif en el input-->
					<input type="text" class="form-control" id="nif" name='nif' value='<?= $nif ?>'>
				</div>
			</div>
			<div class="row mb-3">
				<label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
				<div class="col-sm-10">
					<!--mantener el nombre en el input-->
					<input type="text" class="form-control" id="nombre" name="nombre" value='<?= $nombre ?>'>
				</div>
			</div>
			<div class="row mb-3">
				<label for="direccion" class="col-sm-2 col-form-label">Dirección</label>
				<div class="col-sm-10">
					<!--mantener la direccion en el input-->
					<input type="text" class="form-control" id="direccion" name="direccion" value='<?= $direccion ?>'>
				</div>
			</div>
			<label for="nombre" class="col-sm-2 col-form-label"></label>
			<button type="submit" class="btn btn-success" name='alta'>Alta persona</button>
			<!--Mostrar errores del formulario-->
			<span style="color: red;float:right"><?= $mensajes ?></span>
		</form><br>
		<table class="table table-striped">
			<tr class='table-dark'>
				<th scope="col">NIF</th>
				<th scope="col">Nombre</th>
				<th scope="col">Dirección</th>
				<th scope="col"></th>
			</tr>

			<?php
			//llamada a la funcion crear tablas
			crearTablaPersonas($arrayPersonas);
			?>

		</table>

		<form method='post' action='#' id='formularioBaja'>
			<input type='hidden' id='baja' name='baja'></input>
			<!--añadir confirmacion con javascript-->
			<button type="submit" class="btn btn-danger" onclick="confirmarBaja()" name='baja'>Baja personas</button>
		</form>

		<!--FORMULARIO OCULTO PARA LA MODIFICACION-->
		<form method='post' action='#' id='formularioModi'>
			<input type='hidden' name='nifModi'>
			<input type='hidden' name='nombreModi'>
			<input type='hidden' name="direccionModi">
			<input type='hidden' name='modificar'>
		</form>
	</main>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script type="text/javascript" src='js/scripts.js'></script>
</body>

</html>