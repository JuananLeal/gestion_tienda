<?php

	//Compruebo si existe una sesion, sino redirecciono al login

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
	<title>Usuarios</title>
</head>
<body>

	<?php
 	
 		include "BaseDatos.php";

 		echo "<h1Usuarios</h1>";

 		/*
 		En este caso no haría falta comprobar si el usuario es superadmin, porque ya he lo he validado
 		en el acceso, sin embargo lo hago por si acaso
		*/

 		if(es_SuperAdmin($_SESSION['usuario']) == true){
 			echo "<a href='formUsuario.php?crear'>Crear nuevo usuario</a>";

 			//Llamo a la funcion que me genera el listado de usuarios
 			
 			listaUsuarios();

 		}

 		echo "<br><br><a href='Acceso.php'>Volver</a>";

	
 		?>
</body>
</html>
<?php 
}
?>