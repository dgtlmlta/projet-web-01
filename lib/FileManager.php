<?php

	namespace Stampee;

	class FileManager {
		static private $extension = ".php";
		static private $modelPath = ROOTPATH . "/model/";
		static private $controllerPath = ROOTPATH . "/controller/";
		static private $libPath = ROOTPATH . "/lib/";
		static private $imagesDBPath = "/assets/img/stamps-db/";
		static private $imagesDBFullPath = ROOTPATH . "/assets/img/stamps-db/";
	

		static function model($page){
			return require_once self::$modelPath . $page. self::$extension;
		}

		static function controller($page){
			return require_once self::$controllerPath . $page . self::$extension;
		}

		static function lib($page){
			return require_once self::$libPath . $page. self::$extension;
		}
		
		static function redirect($url = null) {
			header("Location: /" . $url);
		}

		static function storeImage($img, $stampId) {
			$parts = pathinfo($img["name"]);
			$filename = self::$imagesDBFullPath . $stampId . ".{$parts['extension']}";
			
			if(move_uploaded_file($img["tmp_name"], $filename)) {
				return self::$imagesDBPath . $stampId . ".{$parts['extension']}";
			} else {
				return false;
			}
		}
	}
?>