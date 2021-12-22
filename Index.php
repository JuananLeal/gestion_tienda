<?php 
	session_start();
	
	//En caso de usar el boton cerrar sesion en acceso cierro la sesion
	if(isset($_REQUEST["salir"])){
		session_destroy();
	}
	//Si intento entrar al login una vez tengo la sesion me redirecciona
	if(isset($_SESSION['usuario'])){
		header('Location:Acceso.php');
	}else
{
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Index</title>
</head>
<body>

		<!-- Creo el formulario para el login -->

		<form action="" method="POST">
			
			<label>Usuario: </label><input type="text" name="nombre"><br>
			<label>Email: </label><input type="email" name="correo"><br>
			<input type="submit" name="acceder" value="Acceder">

		</form>


		<?php 
		include "BaseDatos.php";
		//Compruebo mediante post si he presionado el boton

			if (isset($_POST['acceder'])){
				//Valido los datos, si algún campo está vacío me dirá que los rellene
				if(!empty($_POST['nombre']) && !empty($_POST['correo'])){
					//Si se cumple todo esto paso con la validacion de los campos
					if (isset($_POST['nombre']) && isset($_POST['correo'])){

						//Llamo a la funcion validar_usuario para comprobar los credenciales
					
						validar_usuario($_POST['nombre'],$_POST['correo']);
					}
				}else{
					echo "Rellena ambos campos";
				}	
			}
		?>

</body>
</html>
<?php  
}
?>