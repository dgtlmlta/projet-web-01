<?php 

	namespace Stampee;
	
	class TwigController {
		private static $ext = ".twig";

		static public function render($template, $data) {
			
			$loader = new \Twig\Loader\FilesystemLoader("view");
		
			$twig = new \Twig\Environment(
				$loader,
				array(
					"auto_reload" => true,
					"cache" => false
				)
			);

			$twig->addGlobal("isLoggedIn", SessionManager::isLoggedIn());
			$twig->addGlobal("canEdit", SessionManager::canEdit());

			if(isset($_SESSION["userFName"])) {
				$twig->addGlobal("userFirstName", $_SESSION["userFName"]);
			}
				
			return $twig->render($template . self::$ext, $data);
		}
	}


?>