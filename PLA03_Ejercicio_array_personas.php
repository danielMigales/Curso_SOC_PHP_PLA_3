<?php
//activar variables de sesión
session_start();

//inicialización de variables

$_SESSION['personas'];
$mensajes = null;
$nif = null;
$nombre = null;
$direccion = null;

//array para guardar las personas

$arrayPersonas = [];

//si existe la variable de sesión substituyo el contenido del array

if (isset($_SESSION['personas'])) {
	$arrayPersonas = $_SESSION['personas'];
}

//ALTA DE PERSONA

if (isset($_POST['alta'])) {

	try {

		//validar datos obligatorios
		//recuperar los datos sin espacios en blanco -trim()-
		//recogida del dni, nombre y direccion
		if (!$nif = trim(filter_input(INPUT_POST, 'nif'))) {
			throw new Exception('Nif obligatorio', 0);
		}
		else{
			$existe= array_key_exists($nif, $arrayPersonas);
			if($existe){
				throw new Exception('Nif existe', 1);
			}
		}

		if (!$nombre = trim(filter_input(INPUT_POST, 'nombre'))) {
			throw new Exception('Nombre', 2);
		} else {
			//guardamos el nombre y dirección en minúsculas con la primera letra en mayúsculas
			$nombre = ucfirst(strtolower($nombre));
			//guardar la persona en el array
			$arrayPersonas[$nif]['nombre obligatorio'] = $nombre;
		}

		if (!$direccion = trim(filter_input(INPUT_POST, 'direccion'))) {
			throw new Exception('Direccion obligatoria', 3);
		} else {
			//guardamos el nombre y dirección en minúsculas con la primera letra en mayúsculas
			$direccion = ucfirst(strtolower($direccion));
			//guardar la persona en el array
			$arrayPersonas[$nif]['direccion'] = $direccion;
		}

		//mensaje de alta efectuada
		if ($nif && $nombre && $direccion) {
			$mensajes = 'Alta efectuada';
		}

		//echo ("<pre>" . print_r($arrayPersonas, true) . "</pre>");
	} catch (Exception $e) {
		$mensajes = $e->getCode() . '. ' . $e->getMessage() . '<br>';
	}
}





//validar que el nif no exista en la base de datos







//limpiar el formulario


//BAJA DE TODAS LAS PERSONAS

//inicializar el array


//BAJA DE LA PERSONA SELECCIONADA EN LA TABLA

//recuperar el nif

//validar nif informado

//borrar la fila del array

//mensaje de baja efectuada


//MODIFICACION DE LA PERSONA SELECCIONADA

//recuperar los datos sin espacios en blanco -trim()-

//validar datos

//validar que el nif no exista en la base de datos

//guardamos el nombre y dirección en minúsculas con la primera letra en mayúsculas

//modificar la persona en el array

//mensaje de modificación efectuada


//CONSULTA DE PERSONAS

//ordenar el array por nif

//confeccionar la tabla con las personas del array


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
			<button type="submit" class="btn btn-success" name=' alta'>Alta persona</button>
			<!--Mostrar errores del formulario-->
			<span><?= $mensajes ?></span>
		</form><br>

		<table class="table table-striped">
			<tr class='table-dark'>
				<th scope="col">NIF</th>
				<th scope="col">Nombre</th>
				<th scope="col">Dirección</th>
				<th scope="col"></th>
			</tr>

			<!--tr>
		      <td>40000000A</td>
		      <td><input type='text' value='O-Ren Ishii' class='nombre'></td>
		      <td><input type='text' value='Graveyard avenue, 66' class='direccion'></td>
		      <td>
		      	<form method='post' action='#'>
		      		<input type='hidden' name='nifBaja' value='40000000A'>
		      		<button type="submit" class="btn btn-warning" name='bajaPersona'>Baja</button>
		      	</form>
		      	<button type="button" class="btn btn-primary" name='modiPersona'>Modificar</button>
		      </td>
		    </tr-->
		</table>

		<form method='post' action='#' id='formularioBaja'>
			<input type='hidden' id='baja' name='baja'></input>
			<button type="submit" class="btn btn-danger" name='baja'>Baja personas</button>
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