<?php 
	namespace Stampee;

	class LogoutController {
			
		public function index() {
			session_destroy();
			FileManager::redirect("xpets");
		}
	}
?>