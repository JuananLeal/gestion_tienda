<?php

	//Compruebo si he iniciado sesion, sino reenvio al login

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
	<title>Formulario Articulos</title>
</head>
<body>
		<?php 
		include "BaseDatos.php";
	
		//Compruebo mediante get, si se vino desde editar, borrar o crear

		if (isset($_GET['editar'])){
			echo "Se va a modificar un artículo nuevo";
			$id = $_GET['editar'];

			//Si vengo de editar relleno los datos del formulario

			$datos = mysqli_fetch_assoc(getProducto($id));
		} elseif(isset($_GET['borrar'])){
			echo "Se va a borrar un artículo";
			$id = $_GET['borrar'];

			//Si vengo de borrar también muestro los datos del formulario rellenados

			$datos = mysqli_fetch_assoc(getProducto($id));
		}else {

			//Si no vengo de ninguno de esos dos entonces vengo de crear, muestro el formulario vacio 

			echo "Se va a añadir un nuevo artículo";
			$id = "";
			$datos = [
				"ProductID" => "",
				"CategoryID" => "",
				"Name" => "",
				"Cost" => "" ,
				"Price" => ""];
		}
	?>
	

		<form action='' method='POST'>

				<!-- Genero el formulario -->

				<label>ID: </label><input type='number' name='ID' value="<?php echo $id; ?>"><br>
				<label>Categoría: </label><select name='categoria'>
				<?php

						//Esta funcion genera el desplegable con los datos de la categoria

						listaCategoria($datos["CategoryID"]);
				?>
				</select><br>
				<label>Nombre: </label><input type='text' name='nombre' value="<?php echo $datos["Name"]; ?>"><br>
				<label>Coste: </label><input type='number' name='coste' value="<?php echo $datos["Cost"]; ?>"><br>
				<label>Precio: </label><input type='number' name='precio' value="<?php echo $datos["Price"]; ?>"><br>

				<?php 

				//Dependiendo de si el formulario era para editar, añadir o borrar genero un botón dependiendo de donde venga

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

						//Llamo a la función que realizará las consultas a la BD

						gestion_Articulo();

				 ?>
				
					

</body>
</html>
<?php	
}
?>