<?php 
	require_once('Helpers/Inflect.php');
	print_r($argv);
	$model = Inflect::singularize($argv[1]);
	$my_file = "migrate.sql";
	$fh = fopen($my_file, 'w');
	fwrite($fh,"CREATE TABLE ".$argv[1]."(\n".$model."_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,\n");
 	
	$temp = "";
	$arr = array();
	
	for($i=2;$i<sizeof($argv);$i++){
		$arr = explode(":",$argv[$i]);
		# arr[0]=field,arr[1]=type
		$temp.=$arr[0];
		
		switch ($arr[1]) {
			case 'string':
				//echo "yess";
				$temp.=" VARCHAR(50),\n";
				break;
			case 'number':
				$temp.=" INT,\n";
				break;
			default:
				# code...
				break;
		}
	}
	$temp.="updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,\ncreated_at TIMESTAMP NOT NULL);";
 	fwrite($fh, $temp);
 	fclose($fh);
 ?>