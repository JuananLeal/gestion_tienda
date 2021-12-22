<?php
	session_start();
	//Para evitar problemas, si no he iniciado sesion me devuelve al login
	if(!isset($_SESSION['usuario'])){
		header('Location:Index.php');
	}else{
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Acceso</title>
</head>
<body>
<?php 
	
	include "BaseDatos.php";

	//Recojo el valor de sesion para utilizar el nombre del usuario que se ha logueado

	$user = $_SESSION['usuario'];

	//Valido el tipo de usuario, para mostrarle las opciones dependiendo de sus permisos
	//llamo a las funciones es_SuperAdmin y tipo_usuario

	if(es_SuperAdmin($user) == true){
		echo "<a href='Articulos.php'>Articulos</a>
			  <a href='Usuarios.php'>Usuarios</a><br>";
	}elseif(tipo_usuario($user) == 1){
		echo "<a href='Articulos.php'>Articulos</a><br>";
	}else{
		echo "<a href='Articulos.php'>Articulos</a><br>";
	}
 
?>
	<!-- Si cierro sesion devuelvo true mediante get, y cerraré la sesion -->
	<br><a href="Index.php?salir=true">Cerrar Sesión</a>


</body>
</html>
<?php 
}
?>