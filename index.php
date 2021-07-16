<?php
	namespace Stampee;

	session_start();

	// Pour les require et include.
	define("ROOTPATH", __DIR__);
	// Fingerprinting
	define("SECRET_SPICE", md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER["REMOTE_ADDR"] . "--éé--"));
	
	require_once __DIR__ . '/lib/FileManager.php';
	require_once __DIR__ . '/vendor/autoload.php';

	FileManager::controller("TwigController");

	FileManager::lib("SessionManager");
	FileManager::lib("dateFunctions");
	FileManager::lib("stringFunctions");

	// Classe parent de tous les DAO.
	FileManager::model("Gateway");
	
	// FileManager::model("LogDAO");

	$uid = (isset($_SESSION["userId"])) ?
		$_SESSION["userId"] :
		null;

	// Enregistrement dans le journal
	// $logDAO = new LogDAO();
	// $logDAO->storeAccessLog($_SERVER["REQUEST_URI"], $_SERVER["REMOTE_ADDR"], $uid);

	$path = ($_SERVER["QUERY_STRING"] != "") ?
		explode("?", $_SERVER["REQUEST_URI"])[0] :
		$_SERVER["REQUEST_URI"];

	//recuperer le chemin (URL) et mettre dans un tableau
	$url = ($path != "/") ?
		explode('/', ltrim($path, "/")) :
		"/";

	/* $url = (explode('/', $_SERVER["REQUEST_URI"])[0] != "") ?
		explode('/', $slug) :
		"/";
	*/
	
	// Pages à ne pas inclure comme referer
	$noref = [
		"membre",
		"mise"
	];
	
	if($url == "/"){
		FileManager::model("AuctionDAO");

		$auctionDAO = new AuctionDAO();
		$queryOptions = [
			"limit" => 3
		];
		
		echo TwigController::render(
			"index",
			[
				"auctions" => $auctionDAO->getNewestAuctionCards($queryOptions)
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

	
	if(!file_exists($controllerPath)) {
		// FileManager::redirect();
		die();
	}
	
	FileManager::controller(ucfirst($requestUrl) . "Controller");

	$controllerName = __NAMESPACE__ . "\\" . ucfirst($requestUrl).'Controller';

	$controller = new $controllerName;
	
	if(!isset($url[1])) {
		echo $controller->index();
		die();
	}

	$method = $url[1];

	// Si la méthode du contrôleur n'existe pas, rediriger.
	if(!method_exists($controller, $method)) {
		FileManager::redirect();
	}

	if(!isset($url[2])) {
		echo $controller->$method();
		die();
	}
		
	$id = $url[2];

	echo $controller->$method($id);
?>