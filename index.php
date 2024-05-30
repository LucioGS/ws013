<?php

	include "modelos/bbdd/usuario.php";

	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri = explode( '/', $uri );
	
	if ($uri[5] == "usuario"){
		
		if (strtoupper($_SERVER["REQUEST_METHOD"]) == 'GET') {
			if (!isset($uri[6])){
				$retornado = listado_usuarios();
				if ($retornado["resultado"] == "ok"){
					logs("acceso a listado de usuarios");
					respuesta(200, "OK", $retornado["valores"]);	
				}else{
					logs($retornado["valores"]);
					respuesta(500, "Internal Server Error", $retornado["valores"]);	
				}	
			}else{
				$retornado = datos_un_usuario($uri[6]);
				if ($retornado["resultado"] == "ok"){
					logs("acceso a datos de usuario ".$uri[6]);
					respuesta(200, "OK", $retornado["valores"]);	
				}else{
					logs($retornado["valores"]);
					respuesta(500, "Internal Server Error", $retornado["valores"]);	
				}
			}
		}
		
		if (strtoupper($_SERVER["REQUEST_METHOD"]) == 'POST') {
			$data = json_decode(file_get_contents('php://input'), true);
			$nombre = $data["datos"]["nombre"];
			$apellidos = $data["datos"]["apellidos"];
			$telefono = $data["datos"]["telefono"];
			$email = $data["datos"]["email"];
			$direccion = $data["datos"]["direccion"];
			$localidad = $data["datos"]["localidad"];
			$user = $data["datos"]["user"];
			$password = $data["datos"]["password"];
			$perfil = $data["datos"]["perfil"];		
			$retornado = nuevo_usuario($nombre, $apellidos, $telefono, $email, $direccion, $localidad, $user, $password, $perfil);	
			if ($retornado == "ok"){
				logs("nuevo usuario creado");
				respuesta(200, "OK", "");	
			}else{
				logs($retornado);
				respuesta(500, "Internal Server Error", "");	
			}

		}
		
		if (strtoupper($_SERVER["REQUEST_METHOD"]) == 'DELETE') {
			$retornado = eliminar_usuario($uri[6]);
			if ($retornado == "ok"){
				logs("usuario eliminado ".$uri[6]);
				respuesta(200, "OK", "");	
			}else{
				logs($retornado);
				respuesta(500, "Internal Server Error", "");	
			}
		}
		
		if (strtoupper($_SERVER["REQUEST_METHOD"]) == 'PUT') {
			$data = json_decode(file_get_contents('php://input'), true);
			$nombre = $data["datos"]["nombre"];
			$apellidos = $data["datos"]["apellidos"];
			$telefono = $data["datos"]["telefono"];
			$email = $data["datos"]["email"];
			$direccion = $data["datos"]["direccion"];
			$localidad = $data["datos"]["localidad"];
			$user = $data["datos"]["user"];
			$password = $data["datos"]["password"];
			$perfil = $data["datos"]["perfil"];
			$retornado = actualizar_usuario($nombre, $apellidos, $telefono, $email, $direccion, $localidad, $user, $password, $perfil, $uri[6]);
			if ($retornado == "ok"){
				logs("usuario actualizado ".$uri[6]);
				respuesta(200, "OK", "");	
			}else{
				logs($retornado);
				respuesta(500, "Internal Server Error", "");	
			}
		}
		
	}else{
		logs("404 Page not found");
		respuesta(404, "Page not found", "KO");	
	}
	
	
    function respuesta($estado, $mensaje_estado, $datos){
		
		header("Content-Type:application/json");
        header("HTTP/1.1 $estado $mensaje_estado");
        $respuesta['estado'] = $estado;
        $respuesta['mensaje_estado'] = $mensaje_estado;
        $respuesta['datos'] = $datos;
        $respuesta_json = json_encode($respuesta);
        echo $respuesta_json;
		
    }
	
	
	function logs($mensaje){
		
		$myfile = fopen("log.txt", "a");
		$fecha = date("Y-m-d H:i:s");
		$bytes = fwrite($myfile, $fecha."  ".$mensaje."\n");
		fclose($myfile);
		
	}
	
  
?>