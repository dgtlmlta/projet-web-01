<?php

	namespace XPetsIntl;

	class FileManager {
		static private $extension = ".php";
		static private $modelPath = ROOTPATH . "model/";
		static private $controllerPath = ROOTPATH . "controller/";

		static private $imagesDBPath = "assets/img/xpet-db/";
		static private $imagesDBFullPath = ROOTPATH . "assets/img/xpet-db/";

		static private $libPath = ROOTPATH . "lib/";

		static private $host = "https://xpets.digitalmilitia.net" . ROOTDIR;

		static function model($page){
			return require_once self::$modelPath . $page. self::$extension;
		}

		static function controller($page){
			return require_once self::$controllerPath . $page. self::$extension;
		}

		static function lib($page){
			return require_once self::$libPath . $page. self::$extension;
		}
		
		static function redirect($url = "xpets") {
			header("Location: " . self::$host . $url);
		}

		static function storeImage($img, $petId) {
			$parts = pathinfo($img["name"]);
			$filename = self::$imagesDBPath . $petId . ".{$parts['extension']}";
			
			if(move_uploaded_file($img["tmp_name"], $filename)) {
				return $filename;
			} else {
				return false;
			}
		}
	}
?>