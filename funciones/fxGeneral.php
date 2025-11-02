<?php
	require_once ("datos.php");
	date_default_timezone_set("America/Managua");

	function depurar($cadena){echo('<script>alert("' . rtrim($cadena) . '")</script>');}
	
	function fxAbrirConexion()
	{
		$msUsuario = $_SESSION["gsUSR"];
		$msClave = $_SESSION["gsPWD"];
		$msBase = $_SESSION["gsBD"];
		$conexion = new PDO('mysql:host=localhost;dbname='.$msBase, $msUsuario, $msClave);
		$conexion->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		$conexion->exec("set names utf8");
		return $conexion;
	}

?>