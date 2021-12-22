<?php

	//Compruebo si existe una sesion creada, sino redirecciono al login

	session_start();
	if(!isset($_SESSION['usuario'])){
		header('Location:Index.php');
	}else{
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Formulario Usuario</title>
</head>
<body>
		<?php 
		include "BaseDatos.php";
		if (isset($_GET['editar'])){
			echo "Se va a modificar un usuario";
			$id = $_GET['editar'];

			//Si presiono editar en usuarios.php, relleno el formulario con los datos del registro que quiero editar

			$datos = mysqli_fetch_assoc(getUsuario($id));
		} elseif(isset($_GET['borrar'])){
			echo "Se va a borrar un usuario";
			$id = $_GET['borrar'];

			//Si presiono borrar en usuarios.php, relleno el formulario con los datos del registro que quiero borrar

			$datos = mysqli_fetch_assoc(getUsuario($id));
		}else {

			//En caso de no venir ni de editar ni de borrar, significa que le he dado a crear por lo tanto el formulario
			//lo genero vacío

			echo "Se va a añadir un nuevo usuario";
			$id = "";
			$datos = [
				"UserID" => "",
				"FullName" => "",
				"Password" => "",
				"Email" => "" ,
				"LastAccess" => "",
				"Enabled" => ""
			];
		}
	?>
	

		<form action='' method='POST'>

			<!-- Muestro el formulario -->

				<label>ID: </label><input type='number' name='ID' value="<?php echo $id; ?>"><br>
				<label>Nombre: </label><input tyme='text' name='nombre' value="<?php echo $datos["FullName"]; ?>"><br>
				<label>Contraseña: </label><input type='password' name='pass' value="<?php echo $datos["Password"]; ?>"><br>
				<label>Correo: </label><input type='email' name='mail' value="<?php echo $datos["Email"]; ?>"><br>
				<label>Último Acceso: </label><input type='date' name='fecha' value="<?php echo $datos["LastAccess"]; ?>"><br>
				<label>Autorizado </label><input type='number' name='autorizado' value="<?php echo $datos["Enabled"]; ?>"><br>
				<?php 

				/*
					Compruebo si vengo de borrar, editar o crear muestro un botón diferente en cada caso
				*/

				if(isset($_GET['borrar'])){
							echo "<input type='hidden' name='id' value=>
							<input type='submit' name='eliminar' value='Borrar'>";			
				}elseif (isset($_GET['editar'])){

					echo "<input type='hidden' name='id' value=>
							<input type='submit' name='modificar' value='Modificar'>";
							
				}elseif(isset($_GET['crear'])){
						echo "<input type='hidden' name='id' value=>
							<input type='submit' name='crear' value='Crear'>";
				}

				
				?>


			</form>

				

				<?php 

						//Llamo a la función que realizará la consulta a la base de datos

						gestion_Usuario();

				 ?>
				
					

</body>
</html>
<?php	
}
?>