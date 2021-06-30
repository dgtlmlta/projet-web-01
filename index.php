<?php
	namespace Stampee;

	session_start();

	define("NS", __NAMESPACE__ . "\\");
	// Pour les require et include.
	define("ROOTPATH", __DIR__ . "/");
	// Fingerprinting
	define("SECRET_SPICE", md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER["REMOTE_ADDR"] . "--éé--"));
	
	require_once __DIR__ . '/lib/FileManager.php';
	require_once __DIR__ . '/vendor/autoload.php';
	
	FileManager::controller("TwigController");
	FileManager::lib("SessionManager");
	FileManager::model("Gateway");
	// FileManager::model("LogDAO");

	
	$uid = (isset($_SESSION["userId"])) ?
		$_SESSION["userId"] :
		null;

	// Enregistrement dans le journal
	// $logDAO = new LogDAO();
	// $logDAO->storeAccessLog($_SERVER["REQUEST_URI"], $_SERVER["REMOTE_ADDR"], $uid);

	//recuperer le chemin (URL) et mettre dans un tableau
	$url = (isset($_SERVER["REQUEST_URI"]) && $_SERVER["REQUEST_URI"] != "/") ?
		explode('/', ltrim($_SERVER["REQUEST_URI"], "/")) :
		"/";

	/* $url = (explode('/', $_SERVER["REQUEST_URI"])[0] != "") ?
		explode('/', $slug) :
		"/"; */
	
	// Pages à ne pas inclure comme referer
	$noref = [
		"authentification",
		"inscription"
	];
	
	if($url == "/"){
		echo TwigController::render(
			"index",
			[
				
			]
		);
		exit();
	}
	if(!in_array($url[0], $noref)) {
		$_SESSION["referer"] = $_SERVER["REQUEST_URI"];
	}

	$requestUrl = $url[0];
	
	//recuperer le controleur
	$controllerPath = __DIR__ . "/controller/" . ucfirst($requestUrl) . "Controller.php";
	
	if(file_exists($controllerPath)) {
		FileManager::controller(ucfirst($requestUrl) . "Controller");
	
		$controllerName = NS . ucfirst($requestUrl).'Controller';
		$controller = new $controllerName;

		if(isset($url[1])) {
			$id = null;

			if(is_numeric($url[1]) && isset($url[2])) {
				echo $controller->index($url[1], $url[2]);
			} else {
				$method = $url[1];

				if(isset($url[2]) && is_numeric($url[2])) {
					$id = $url[2];
				}
				
				if(method_exists($controller, $method)) {
					echo $controller->$method();		
				} else {
					FileManager::redirect();	
				}
			}				
		} else {
			echo $controller->index();
		}			
	} else {
		FileManager::redirect();
	}

?>