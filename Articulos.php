<?php

	//Compruebo si hay una sesion de usuario iniciada

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
	<title>Articulos</title>
</head>
<body>

	<?php
 	
 		include "BaseDatos.php";

 		echo "<h1>Articulos</h1>";

 		/*
 		Ahora dependiendo del tipo de usuario le muestro diferentes opciones, si es superAdmin
 		tendrá botones de modificar y borrar los registros.

 		El botón para crear productos, lo muestro aparte 
 		
 		*/

 		if(es_SuperAdmin($_SESSION['usuario']) == true){
 			echo "<a href='formArticulos.php?crear'>Crear nuevo producto</a>";
 			listaArticulos_SuperAdmin();

 		}elseif(tipo_usuario($_SESSION['usuario']) == 1){
 			echo "<a href='formArticulos.php?crear'>Crear nuevo producto</a>";
 			ListaArticulos();
 		}else{
 			ListaArticulos();
 		}

 		//Para volver al login

 		echo "<br><br><a href='Acceso.php'>Volver</a>";

	
 		?>
</body>
</html>
<?php 
}
?>