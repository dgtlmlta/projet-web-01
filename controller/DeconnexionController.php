<?php 
	namespace Stampee;

	class DeconnexionController {
			
		public function index() {
			session_destroy();
			FileManager::redirect();
		}
	}
?>