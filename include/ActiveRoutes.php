<?php

define('ROOT','/~kfircohen/copier/');	
	/**
	* 
	*/
class ActiveRoutes {
		
	public $root ;
	public $resources = array();
	public $uri ;
	public $matches = array();

	function __construct(){
		session_start();
		$this->uri =  explode("/",$_SERVER['REQUEST_URI']);
		$this->root ;
		require_once '../routes.php';

		// die(print_r($this->uri));
		$this->Genrate_Route();
		
	}

	public function Genrate_Route(){
		// die(print_r($this->uri));
		if(isset($this->resources[$this->uri[3]])){
			if($_SERVER['REQUEST_METHOD']=="GET"  ) {

				$_GET["new"] = ($this->uri[4] == 'new') ? true : null ;
				$_GET["id"] = ($this->uri[4] != '') ? $this->uri[4] : null ;
				$_GET["edit"] = ($this->uri[5] == 'edit') ? $this->uri[4] : null ;
				require_once ("../controllers/".$this->uri[3]."_controller.php");					
			}	

			 else if($_SERVER['REQUEST_METHOD']=="POST"  ) {
			 	// print_r($this->uri);
			 	$_POST["delete"] = $this->uri[4];
			 }	
		}
		else if(isset($this->matches[$this->uri[3]])) {
			$arr =  explode("/", $this->matches[$this->uri[3]]);
			
			if($arr[1]!="id" || $arr[1] != "edit"){
				if(is_numeric($arr[1]))
					$_GET["id"] = $arr[1];
				else
					$_GET[$arr[1]] = true;
				
					require_once ("../controllers/".$arr[0]."_controller.php");
			}
		}
		else{  // go to root //
			$arr = explode("/", $this->root);
			
			$_GET["root"] = $arr[1];
			
			require_once ("../controllers/".$arr[0]."_controller.php");
			// echo "Error !!!,can't find the page ";
		}
	}

	public function Resources($resource) {
			$this->resources[$resource] = true;
		}
		public function Root($path){
			$this->root = $path;
			$_SESSION["root"] = $this->root;
		}
		public function Match($url,$path){
			$this->matches[$url] = $path;
		}

	}

	$r = new ActiveRoutes();
?>