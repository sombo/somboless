<?
//require_once('../models/Welcome.php');
require_once('../include/Controller.php');

// /**
// * 
// */
class welcome_controller extends Controller
{
	
	public $obj ;
	
	function __construct($hasModel){
		$this->Page_Title = "Welcome";

		//$this->obj = new Welcome();

		parent::__construct($hasModel);

	}
	
	function create(){
		$this->lead = "Create new Welcome";
		$this->render("new");
	}

	function index(){
		$this->lead = "All Welcome";
		$this->render("index");
		
	}

	function show(){
	//	$this->obj =  new Welcome($this->d->Find_By($_GET["id"]));
		$this->render("show");
	}

	function edit(){
		$this->lead = "Edit Welcome";
	//	$this->obj =  new Welcome($this->d->Find_By($_GET["edit"]));
		$this->render("edit");
	}
	function delete(){
	//	$this->obj = new Welcome($this->d->Find_By($_POST["delete"]));
	//	if($this->obj->Delete())
	//		$this->ajax("ok");

	//	else
	//		 $this->ajax($_SESSION["errors"]);
	}
}

	$a = new welcome_controller(false);
?>
