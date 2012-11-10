<!DOCTYPE html>
<html >
<head>
	<title><?echo $this->Page_Title;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=9" /> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="/copier/js/js/jquery-ui.custom.min.js"></script>

</head>
<style type="text/css">
	a{
		margin-right: 5px;
	}

	#debug_Status{
		border: 1px solid green;
		background-color: #ddd;
		clear: both;
	}
	.all_Objects{
		border: 1px solid #EFF;
		background-color: #DDD;
		margin-top: 3px;
		width: 150px;
		float: left;
		margin-bottom: 10px;
		margin-right: 20px;
	}
	.form{
		margin-left: 420px;
		margin-bottom: 10px;
		width: 380px;
		border: 1px solid #ddd;
		font-family: "Arial, Helvetica, sans-serif";
	}
	.form h3{text-align: center;}
	input[type="button"], input[type="submit"], input[type="reset"] {
		width:100px;
	}
	#buttons{
		text-align: center;
		
	}
	#tblForm{margin:auto;}
	select{width:155px;}
	input{width:150px;}
	.error_array{
		/*font-size: 10px;*/
		font-family: arial;
		background: #C10;
	}
	.info_array{
		font-family: arial;
		background: #C90;
	}
</style>
<body>
	
<?php

	
	include $_SESSION["yield"];
	$_SESSION["yield"] = null;
	//	session_destroy();
	
	
	echo "<div id=debug_Status>no errors</div>";
?>

<script type="text/javascript">
	 String.prototype.trim = function () {
    	return this.replace(/^\s*/, "").replace(/\s*$/, "");
	 }
	 $(function(){
			$(".del").click(function(){
				alert("dfd");
				var id = $(this).parent().attr("id");
				var thiss = $(this).parent();
				//$(this).parent().remove();
				var url = window.location.href;
				var urlSplit = url.split("/"); 
				var controller = urlSplit[urlSplit.length-1];
				
				$.ajax({
					url: controller,
					data:"delete="+id,
					type:'POST',
					dataType:'text',
					success:function(msg){
						if(msg.trim() == "ok")
							thiss.remove();
						
						else
							console.log(msg);
					},
				});
				//$(this).parent().remove();
				return false;
			});
	 });
	 
	//$('p',$('.all_Objects')).filter(':even').css('background-color', 'yellow');
</script>
</body>
</html>


