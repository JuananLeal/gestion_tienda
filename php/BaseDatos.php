<?php  


	//Creo la conexión con la base de datos

	function crearConexion($database) {

		// Datos de conexión
		$host = "localhost";
		$user = "root";
		$password = "";

		// Establecemos la conexión con la base de datos
		$conexion = mysqli_connect($host, $user, $password, $database);

		//Devolvemos el valor de la conexion
		return $conexion;
	}


	//Valido en Index.php el usuario

	function validar_usuario($usr,$mail){


		$conexion = crearConexion("pac3_DAW");

		$consulta = "SELECT * FROM user WHERE FullName = '$usr' and Email ='$mail'";
		$resultado = mysqli_query($conexion,$consulta);

		if(mysqli_num_rows($resultado) > 0){

			//Si existe un usuario con esa combinacion de mail y nombre, le muestro un mensaje para que acceda

			echo "Bienvenido " . $usr . ", pulsa <a href='Acceso.php'>AQUÍ</a> para continuar. ";

			//Actualizo la fecha de ultimo acceso y genero la sesion

			ultimoAcceso($usr);
			return $_SESSION['usuario']= $usr;
						
		}else{
			//Si no existe devuelvo un mensaje de error
			echo "Credenciales erroneas";

		}

		mysqli_close($conexion);
	}

	//Actualiza la última fecha de acceso 

	function ultimoAcceso($usr){

		$conexion = crearConexion("pac3_DAW");

		$fecha = date('Y-m-d');

		$consulta = "UPDATE user SET LastAccess = '$fecha' WHERE FullName = '". $usr ."'";

		$resultado = mysqli_query($conexion,$consulta);

		mysqli_close($conexion);
	}

	//Valido el tipo de usuario, si es autorizado o no

	function tipo_usuario($usr){

		$conexion = crearConexion("pac3_DAW");

		$consulta = "SELECT Enabled FROM user WHERE FullName = '". $usr . "'";

		$resultado = mysqli_query($conexion,$consulta);

		$row = mysqli_fetch_assoc($resultado);

		//Devuelve 0 o 1

		return $row["Enabled"];

		mysqli_close($conexion);

	}

	//Compruebo si el usuario es superAdmin en Acceso.php

	function es_SuperAdmin($usr){

		//Busco el ID del usuario que entra

		$conexion = crearConexion("pac3_DAW");
		$consulta = "SELECT UserID FROM user WHERE FullName = '". $usr ."'";
		$resultado = mysqli_query($conexion,$consulta);

		//Compruebo que me devuelve valores, ante de acceder a ellos

		if(mysqli_num_rows($resultado) > 0){
			$row = mysqli_fetch_assoc($resultado);
			$id = $row["UserID"];


			//Busco al superadmin con el ID en la tabla setup

			$consul_admin = "SELECT * FROM setup WHERE SuperAdmin = '". $id . "'";
			$resul_admin = mysqli_query($conexion,$consul_admin);

			if(mysqli_num_rows($resul_admin) > 0){

				//Devuelvo true si existe un superAdmin con ese ID
				return true;
			}

		}

		mysqli_close($conexion);

	}

	//En la lista de articulos tengo que mostrar las categorias por nombre no ID
	//asi que busco con el ID el nombre de la categoria en la tabla category

	function swap_categoria($categoriaID){
		$conexion = crearConexion("pac3_daw");

		$consulta = "SELECT Name FROM category WHERE CategoryID ='". $categoriaID . "'";

		$resultado = mysqli_query($conexion,$consulta);

		if (mysqli_num_rows($resultado) > 0) {
			$dato = mysqli_fetch_assoc($resultado);
			return $dato["Name"];
		} else {
			echo "Error al obtener la categoria";
		}
		mysqli_close($conexion);
	}
	
	//Genero la lista con los registros de la tabla Articulos

	function ListaArticulos(){

		$conexion = crearConexion("pac3_daw");

			//Si ya está creado pagina lo guardo, sino el valor es 1

			if(isset($_GET['pagina'])){
				$pagina = $_GET['pagina'];
			}else{
				$pagina = 1;
			}

		//Dependiendo de la pagina, muestro el numero de registros en la misma

		$reg_pag = 10;

		$desde = ($pagina-1) * $reg_pag;

		$consulta = "SELECT ProductID, Name, Cost, Price, CategoryID FROM product LIMIT $desde , $reg_pag";
		$consulta_filas = "SELECT * FROM product";
					
		$resultado = mysqli_query($conexion, $consulta);
		$resultado_filas = mysqli_query($conexion,$consulta_filas);

		//Saco el numero total de paginas dependiendo de la cantidad de registros de la tabla

		$total_reg = mysqli_num_rows($resultado_filas)/$reg_pag;

		//Si la consulta me devuelve resultados, dibujo el conjunto de registros

			if (mysqli_num_rows($resultado) > 0) {
			if (is_string($resultado)) {
				echo $resultado;
			} else {
				echo "<table>\n 
						<tr>\n
							<th>ID</th>\n
							<th>Categoría</th>\n
							<th>Nombre</th>\n
							<th>Coste</th>\n
							<th>Precio</th>\n";
				echo "</tr>\n";

				while ($fila = mysqli_fetch_assoc($resultado)) {
					//Llamo a swap_categoria para que me muestre el nombre de la categoria no el ID
					echo "<tr>\n
							<td>" . $fila["ProductID"] . "</td>\n
							<td>" . swap_categoria($fila["CategoryID"]) . "</td>\n
							<td>" . $fila["Name"] . "</td>\n
							<td>" . $fila["Cost"] . "</td>\n
							<td>" . $fila["Price"] . "</td>\n";
					echo "</tr>";			
				}
				echo "</table>";

				//Genero la paginacion, si estoy en la primera pagina o en la última quito los botones para evitar errores

				if($pagina <= 1){
					echo "&nbsp &nbsp <a href='Articulos.php?pagina=".($pagina+1)."'>>>></a>";
				}elseif($pagina >= $total_reg){
					echo "<a href='Articulos.php?pagina=".($pagina-1)."'><<<</a>";
				}else{
					echo "<a href='Articulos.php?pagina=".($pagina-1)."'><<<</a> &nbsp &nbsp <a href='Articulos.php?pagina=".($pagina+1)."'>>>></a>";
				}
			}                              

			}

			mysqli_close($conexion);
		}

		//Genero la lista con los registros de la tabla Articulos, para los superAdmin

		function listaArticulos_SuperAdmin() {

			$conexion = crearConexion("pac3_daw");

			//Si ya está creado pagina lo guardo, sino el valor es 1

			if(isset($_GET['pagina'])){
				$pagina = $_GET['pagina'];
			}else{
				$pagina = 1;
			}

			//Dependiendo de la pagina, muestro el numero de registros en la misma

		$reg_pag = 10;

		$desde = ($pagina-1) * $reg_pag;

		$consulta = "SELECT ProductID, Name, Cost, Price, CategoryID FROM product LIMIT $desde , $reg_pag";
		$consulta_filas = "SELECT * FROM product";
					
		$resultado = mysqli_query($conexion, $consulta);
		$resultado_filas = mysqli_query($conexion,$consulta_filas);

		//Saco el numero total de paginas dependiendo de la cantidad de registros de la tabla

		$total_reg = mysqli_num_rows($resultado_filas)/$reg_pag;

		//Si la consulta me devuelve resultados, dibujo el conjunto de registros

			if (mysqli_num_rows($resultado) > 0) {
			if (is_string($resultado)) {
				echo $resultado;
			} else {
				echo "<table>\n 
						<tr>\n
							<th>ID</th>\n
							<th>Categoría</th>\n
							<th>Nombre</th>\n
							<th>Coste</th>\n
							<th>Precio</th>\n
							<th>Manejo</th>\n";
				echo "</tr>\n";

				while ($fila = mysqli_fetch_assoc($resultado)) {
					//Llamo a swap_categoria para que me muestre el nombre de la categoria no el ID
					//Como este es superAdmin, también genero los botones de editar y borrar, sus valores se pasan por GET
					echo "<tr>\n
							<td>" . $fila["ProductID"] . "</td>\n
							<td>" . swap_categoria($fila["CategoryID"]) . "</td>\n
							<td>" . $fila["Name"] . "</td>\n
							<td>" . $fila["Cost"] . "</td>\n
							<td>" . $fila["Price"] . "</td>\n
							<td><a href='formArticulos.php?editar=". $fila["ProductID"] ."'>Editar</a>
							<td><a href='formArticulos.php?borrar=". $fila["ProductID"] ."'>Borrar</a>
								
							</td>";
					echo "</tr>";			
				}
				echo "</table>";

				//Genero la paginacion, si estoy en la primera pagina o en la última quito los botones para evitar errores

				if($pagina <= 1){
					echo "&nbsp &nbsp <a href='Articulos.php?pagina=".($pagina+1)."'>>>></a>";
				}elseif($pagina >= $total_reg){
					echo "<a href='Articulos.php?pagina=".($pagina-1)."'><<<</a>";
				}else{
					echo "<a href='Articulos.php?pagina=".($pagina-1)."'><<<</a> &nbsp &nbsp <a href='Articulos.php?pagina=".($pagina+1)."'>>>></a>";
				}
			}                              

			}

			mysqli_close($conexion);

		}

		//Dependiendo del id del producto, saco los datos del mismo para luego generar el formulario formArticulos.php

		function getProducto($id){
		$conexion = crearConexion("pac3_daw");

		$consulta = "SELECT Name,Cost,Price,CategoryID FROM product WHERE ProductID ='" . $id . "'";

		$resultado = mysqli_query($conexion, $consulta);


		if (mysqli_num_rows($resultado) > 0) {
			return $resultado;
		} else {
			echo "Error al obtener los productos";
		}
		mysqli_close($conexion);
	}

	//Saco los datos de la tabla category para luego generar el desplegable categoria

		function getCategoria() {
		$conexion = crearConexion("pac3_daw");

		$consulta = "SELECT * FROM category";

		$resultado = mysqli_query($conexion,$consulta);

		if (mysqli_num_rows($resultado) > 0) {
			return $resultado;
		// Si no, enviamos un mensaje de error.
		} else {
			echo "Error al obtener las categorias";
		}
		mysqli_close($conexion);
	}

	//Genera la lista desplegable en el formArticulos.php

	function listaCategoria($id_cat) {
		$datos = getCategoria();
		if (is_string($datos)) {
			echo $datos;

		} else {
			while ($fila = mysqli_fetch_assoc($datos)) {
				// NUEVO: Identifico cuál tengo que mostrar por defecto.	
				if ($id_cat == $fila["CategoryID"]) {
					echo "<option selected='true' value='" . $fila["CategoryID"] . "'>" . $fila["Name"] . "</option>";
				} else {
					echo "<option value='" . $fila["CategoryID"] . "'>" . $fila["Name"] . "</option>";
				}
				
			}
		}
		}
	
	//Realiza las consultas a la base de datos, sobre la tabla product

	function gestion_Articulo(){

		$conexion = crearConexion("pac3_daw");

		//Si manda los datos por post los almaceno

		if(isset($_POST['ID']) && isset($_POST['nombre']) && isset($_POST['categoria']) && isset($_POST['coste']) && isset($_POST['precio'])) {
		$id_producto = $_POST['ID'];
		$nombre = $_POST['nombre'];
		$categoria = $_POST['categoria'];
		$coste = $_POST['coste'];
		$precio = $_POST['precio'];
		

		//Dependiendo del boton que he generado en el formulario, realizo una acción distinta

		if(isset($_POST['crear'])){

			$consulta_crear = "INSERT INTO product (Name,Cost,Price,CategoryID) VALUES ('$nombre',$coste,$precio,$categoria) ";

			$resultado_crear = mysqli_query($conexion,$consulta_crear);

			echo "<br>Se ha creado el producto";
			echo "<br><a href='Articulos.php'>Volver</a>";
		
		}

	
		if(isset($_POST['eliminar'])){

			$consulta_eliminar = "DELETE FROM product WHERE ProductID = '" . $id_producto . "'";

			$resultado_eliminar = mysqli_query($conexion,$consulta_eliminar);

			echo "<br>Se ha borrado el producto";
			echo "<br><a href='Articulos.php'>Volver</a>";
		}
	
		if(isset($_POST['modificar'])){

			$consulta_modificar = "UPDATE product SET Name = '$nombre',Cost = $coste, Price = $precio, CategoryID = $categoria WHERE ProductID = '" . $id_producto . "'";

			$resultado_modificar = mysqli_query($conexion,$consulta_modificar);

			echo "<br>Se ha modificado el producto";
			echo "<br><a href='Articulos.php'>Volver</a>";
		}

	}

		mysqli_close($conexion);
	}

	//Genero la lista de los usuarios

	function listaUsuarios(){
		$conexion = crearConexion("pac3_daw");

		$consulta = "SELECT UserID,FullName,Email,LastAccess,Enabled FROM user";
				
		$resultado = mysqli_query($conexion, $consulta);
	
			if (mysqli_num_rows($resultado) > 0) {
			if (is_string($resultado)) {
				echo $resultado;
			} else {
				echo "<table>\n 
						<tr>\n
							<th>ID</th>\n
							<th>Nombre</th>\n
							<th>Email</th>\n
							<th>Último Acceso</th>\n
							<th>Enabled</th>\n
							<th>Manejo</th>";
				echo "</tr>\n";

				while ($fila = mysqli_fetch_assoc($resultado)) {
					echo "<tr>\n
							<td>" . $fila["UserID"] . "</td>\n
							<td>" . $fila["FullName"] . "</td>\n
							<td>" . $fila["Email"] . "</td>\n
							<td>" . $fila["LastAccess"] . "</td>\n
							<td>" . $fila["Enabled"] . "</td>";
							if($fila["FullName"] != $_SESSION['usuario']){
							echo "<td><a href='formUsuario.php?editar=". $fila["UserID"] ."'>Editar</a>
							<td><a href='formUsuario.php?borrar=". $fila["UserID"] ."'>Borrar</a>
							</td>";
						}
					echo "</tr>";			
				}
				echo "</table>";

			
			}                              

			}

			mysqli_close($conexion);
	}


	//Saco los datos para mostrarlo en formUsuario.php

	function getUsuario($id){
		$conexion = crearConexion("pac3_daw");

			$consulta = "SELECT FullName,Password,Email,LastAccess,Enabled FROM user WHERE UserID = '". $id ."'";

			$resultado = mysqli_query($conexion,$consulta);

			if (mysqli_num_rows($resultado) > 0) {
				return $resultado;
			
			} else {
				echo "Error al obtener las categorias";
			}
			mysqli_close($conexion);
	}

	//Guardo los datos del formulario formUsuario.php

	function gestion_Usuario(){

		$conexion = crearConexion("pac3_daw");

		//Compruebo si se han generado los valores por POST

		if(isset($_POST['ID']) && isset($_POST['nombre']) && isset($_POST['pass']) && isset($_POST['mail']) && isset($_POST['fecha']) && isset($_POST['autorizado'])) {
		$id_usr = $_POST['ID'];
		$nombre = $_POST['nombre'];
		$pass = $_POST['pass'];
		$mail = $_POST['mail'];
		$fecha = $_POST['fecha'];
		$autorizado = $_POST['autorizado'];
		
		//Dependiendo del boton que he generado en el formulario realizo una consulta a la BD
		

			if(isset($_POST['crear'])){

				$consulta_crear = "INSERT INTO user (FullName,Password,Email,LastAccess,Enabled) VALUES ('$nombre','$pass','$mail','$fecha',$autorizado) ";

				$resultado_crear = mysqli_query($conexion,$consulta_crear);

				echo "<br>Se ha creado el usuario";
				echo "<br><a href='Usuarios.php'>Volver</a>";
			
			}

		
		//cogiendo el post que mandan los botones del formulario
	
			if(isset($_POST['eliminar'])){

				$consulta_eliminar = "DELETE FROM user WHERE UserID = '" . $id_usr . "'";

				$resultado_eliminar = mysqli_query($conexion,$consulta_eliminar);

				echo "<br>Se ha borrado el usuario";
				echo "<br><a href='Usuarios.php'>Volver</a>";
			}
	
			if(isset($_POST['modificar'])){

				$consulta_modificar = "UPDATE user SET FullName = '$nombre',Password = '$pass', Email = '$mail', LastAccess = '$fecha', Enabled = $autorizado WHERE UserID = '" . $id_usr . "'";

				$resultado_modificar = mysqli_query($conexion,$consulta_modificar);

				echo "<br>Se ha modificado el usuario";
				echo "<br><a href='Usuarios.php'>Volver</a>";
			}

		}

		mysqli_close($conexion);
	}


	/**/