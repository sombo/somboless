<?
//$server_name=$_SERVER['SERVER_NAME'] . ':81';
$server_name=$_SERVER['SERVER_NAME']."/~kfircohen/copier";
$_serv="http://" . $server_name;

// include_once ("../boot/functions_boot.php");

?>

<!DOCTYPE html>
<html dir="rtl" lang="he/il" >
<head>
	<title><?echo $this->site_name."/".$this->Page_Title;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=9" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<? @include_once ("../views/boot/top.php");?>
	<link rel="stylesheet" href="http://<?=$server_name?>/assets/stylesheets/application.css" /> 
</head>

<body>
<!-- header -->
<? @include_once ("../views/shared/_head.php");?>
<div class="container">
  <div class="row">
 	<div class="span4 bs-docs-sidebar " >	
 		<ul class="nav nav-list bs-docs-sidenav">
 			<li><a href="#test"><i class="icon-chevron-right"></i>aaaa</a></li>
 			<li><a href="#foot">vvvv</a></li>	
 		</ul>
 	</div>
	<div class="span5 offset3">
	<?		
		include $_SESSION["yield"];
		$_SESSION["yield"] = null;
	?>
	</div>
 </div>
</div> <!-- /container -->
	<? @include_once ("../views/shared/_foot.php");?>
	<? @include_once ("../views/shared/scripts.php");?>
</body>
</html>


