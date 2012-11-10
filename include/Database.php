<?php


require_once('../include/Constants.php');
require_once('../Helpers/Inflect.php');

//
class Database {
	
	public    $is_obj;
	public    $errors = array();  				// holds validation errors;
	public    $form = array();    				// holds the form details;
	public    $primary_key;
	public    $recordsLength;     				// rows length;
	public 	  $Childs_Records = array(); 		// holds all objects childs;
	public    $Parent_Records = array(); 		// holds all objects parents;
	public    $keys = array();           		// holds all table keys;
	public    $object_Records = array();		// holds object records;
	public    $object_id;						
	public    $tableRecord = array();			// holds the all table;
	private   $status = "none"; 
	public    $table_Fields_Names = array();	
	private   $db; 
	private   $table;
	
	
	protected $connect;

	function __construct($table,$records,$is_obj = false) {			 
		/* init process */
		$this->table = $table;
		$this->connect = mysql_connect(HOST,USER,PASS) ?
		 					$this->status = "connected" : $this->status = mysql_error();
		
		mysql_set_charset('utf8');
		$this->db = mysql_select_db(DB) ? $this->status = "db selected" : $this->status = mysql_error();
		//die($this->status);
		$this->table_Fields_Names = $this->Set_Table_Fields_Names($table);
		$this->primary_key = $this->table_Fields_Names[0];
		$this->tableRecord = $this->Set_Table_Record($table);
		$this->recordsLength = sizeof($this->tableRecord);
		$this->form = $this->Set_Form_Object();
		$this->is_obj = $is_obj;
		/* check if the call is for specific obj or for the whole table */
		if($is_obj){
			if($records != null){
				$this->Set_Object_Records($records);
				/* check if obj has childs and store the details */
				if (is_array($this->Has_Many)) {
					$this->Set_Object_childs();
				}
				if (is_array($this->Belongs_To)) {
					$this->Set_Object_Parent();
				}
			}
		}
		
			
		
	}
	private function Set_Form_Object(){
		//echo $this->table;
		$arr = array();
		$q = "SELECT * FROM ".$this->table.";";
		$res = mysql_query($q);
		if(!$res)
			die("error no model ".$this->model);
		$i = 0 ;
		while ($i < mysql_num_fields($res)) {
			 if(!strpos(mysql_field_flags($res, $i),"primary")){
				
				if(mysql_field_type($res, $i) == "string")
					$arr[$this->table_Fields_Names[$i]]= "text";
				else if(mysql_field_type($res, $i) == "blob")
					$arr[$this->table_Fields_Names[$i]] = "textarea";
				else if(mysql_field_type($res, $i) == "int")
					$arr[$this->table_Fields_Names[$i]] = "number";
			 }
			
			$i++;
		}

		//$arr["save"] = "hidden";

		return $arr;
	}

	public function get_json(){
		echo json_encode($this->tableRecord);
	}
	public function Parent($table){return $this->Belongs_To[$table];}
	
	private function Set_Object_Parent(){
		/* return parent id */
		foreach ($this->Belongs_To as $parent => $val) {
				$field = $this->Match_Primary_Key_For_Table($parent);
			//	$this->Belongs_To[$parent] = $this->object_Records[$field];
				 $this->Parent_Records[$parent][$field] = $this->object_Records[$field];
				// echo $field;
				 $q = 'SELECT '.$val.' FROM '.$parent.' WHERE '.$field.'='.$this->object_Records[$field];
				 $res = mysql_query($q); 
				 if(!$res)	
				 	echo (mysql_error().$q);
				 $row = mysql_fetch_row($res);
				 $this->Parent_Records[$parent][$val] = $row[0]; 
		}
	}

	private function Match_Primary_Key_For_Table($table){
		foreach ($this->table_Fields_Names as $field) {
			$str = Inflect::singularize($table)."_id";
			if($str == $field)
				return $str;
		}
	}

	private function Set_Object_childs(){
		
		foreach ($this->Has_Many as $val) {
			$i = 0;
			$q = "SELECT * FROM $val WHERE ".$this->primary_key." = ".$this->object_id.";";
			
			$res = mysql_query($q);
			while ( $row = mysql_fetch_assoc($res)) {
				 $this->Childs_Records[$val][$i++] = $row;
			}
		}
	}

	private function Set_Table_Record($table) {
		$arr = array();
		$res = mysql_query("SELECT * FROM $table;");
		
		while ($row = mysql_fetch_assoc($res)) {
			$id = $row[$this->primary_key];
			$arr["$id"] = $row;
			
		}
		

		return $arr;
	}

	private function Set_Table_Fields_Names($table){
		$arr = array();
		$res = mysql_query("SELECT * FROM $table;");
		$i = 0;
		$j = 0;
		while ($i < mysql_num_fields($res)) {
			$arr[$i] = mysql_field_name($res, $i);
			$key = mysql_field_flags($res, $i);
			
			if(strpos($key, "unique"))
				$this->keys[$arr[$i]]= "unique"; 
			else if(strpos($key,"primary"))
				$this->keys[$arr[$i]] = "primary";
			else if(strpos($key,"key"))
				$this->keys[$arr[$i]] = "forigen";
			$i++;
		}
		
	
		return $arr;

	}
	function Get_Table_Records() { return $tableRecord; }
	
	function Find_By($field,$value=false){
		
		if (!$value){
			$this->object_id = $field;
			return $this->tableRecord[$field];
		}
		//echo $this->object_id;
		if($this->keys[$field] == null)
			return null;

			
		foreach ($this->tableRecord as $idKey => $row) {
			if ($this->tableRecord[$idKey][$field] == $value){	 
				return $this->tableRecord[$idKey];
			}
		}
	}

	function Update(){
		
		$this->tableRecord[$this->object_id] = $this->object_Records;
		
	}
	function prettyDate($date){ 
		 $time = @strtotime($date); 
		 $now = time(); 
		 $ago = $now - $time; 
		 if($ago < 60){ 
		 	$when = round($ago); 
		 	$s = ($when == 1)?"שנייה":"שניות"; 
		 	return "עודכן לפני $when $s "; 
		 }
		 elseif($ago < 3600){ 
		 	$when = round($ago / 60); 
		 	$m = ($when == 1)?"דקה":"דקות"; 
		 	return "עודכן לפני $when $m "; 
		 }
		 elseif($ago >= 3600 && $ago < 86400){ 
		 	$when = round($ago / 60 / 60); 
		 	$h = ($when == 1)?"שעה":"שעות"; 
		 	return "עודכן לפני $when $h "; 
		 }
		 elseif($ago >= 86400 && $ago < 2629743.83){ 
		 	$when = round($ago / 60 / 60 / 24); 
		 	$d = ($when == 1)?"יום":"ימים"; 
		 	return "עודכן לפני $when $d "; 
		 }
		 elseif($ago >= 2629743.83 && $ago < 31556926){ 
		 	$when = round($ago / 60 / 60 / 24 / 30.4375); 
		 	$m = ($when == 1)?"חודש":"חודשים"; 
		 	return "עודכן לפני $when $m "; 
		 }
		 else{ 
		 	$when = round($ago / 60 / 60 / 24 / 365); 
		 	$y = ($when == 1)?"שנה":"שנים"; 
		 	return "עודכן לפני $when $y "; 
		 } 
	} 
	
	function Set_Object_Records($records){
		$this->object_Records = $records;
		$this->object_id = $records[$this->primary_key];
		//echo $this->object_id;
	}
	function All(){
		$fieldsLength = sizeof($this->table_Fields_Names);
		echo "<div  id=test><ul>";
		foreach ($this->tableRecord as $field => $value) {					
			echo "<li><div id=$field>";
			echo   "<span class=content>".$value[$this->table_Fields_Names[1]]."</span>";
			echo   "<span class=timestamp>".$this->prettyDate($value["updated_at"])."</span>"; 
			echo   "<div id=act>";
			echo   		"<a class=action href=".$field."/edit>ערוך</a>";
			echo   		"<a class=action href=".$field.">הצג</a>";
			echo   		"<a class='action del' href=".$this->table." class=del>מחק</a><hr>";
			echo 	"</div>";	
			echo  "</div></li>";

				 
	 
	 	}
			echo "</div>";
		
	}
	private function Check_Validation_Condition($key,$field,$edit){
		switch ($key) {
			case 'email':
				$regex = '/^[a-zA-Z0-9_+-]+@[a-zA-Z]+\.[a-zA-Z]+$/';
				 if(!preg_match($regex,trim($_POST[$field])))
				 	$this->errors [] = "mail is not valid";
			break;
			
			case 'unique':
					
				 	$q = "SELECT ".$this->primary_key.",".$field." FROM ".$this->table." WHERE ".$field." = '".$this->object_Records[$field]."';";
				 	$res = mysql_query($q);		
					$primary = mysql_fetch_row($res);
					//die($primary[1]);
					if(mysql_num_rows($res)>0 && $primary[1] != $this->object_Records[$field] && $edit)
						$this->errors [] = $this->object_Records[$field]." alerady exists";
				 
			break;
			
			case 'empty':
				if($_POST[$field]==null)
					$this->errors [] = "$field is empty";

			break;

			case (preg_match('/max.*/', $key) ? true : false):
					$pos = strpos($key,"(");
					$con = $key{$pos+1};	
					$ll = trim($this->object_Records[$field]);
					$len = strlen($ll);
					$tempCond = $con;
					if(preg_match('/[א-ת]+$/', $this->object_Records[$field]))
						$tempCond*=2;
					
					if($len > $tempCond)
						$this->errors [] = "$field max $con chars ";
			break;

			case (preg_match('/min.*/', $key) ? true : false):
					$pos = strpos($key,"(");
					$con = $key{$pos+1};	
					$ll = trim($this->object_Records[$field]);
					$len = strlen($ll);
					$tempCond = $con;
					if(preg_match('/[א-ת]+$/', $this->object_Records[$field]))
						$tempCond*=2;
					
					if($len < $tempCond)
						$this->errors [] = "$field min $con chars ";
			break;

			default:
				unset($this->errors);
				break;
		}

	}
	private function Validate($edit){
		foreach ($this->validates as $field => $check) {
			if(is_array($check)){

				foreach ($check as $key ) {
					$this->Check_Validation_Condition($key,$field,$edit);
				}
			}
			else
				$this->Check_Validation_Condition($check,$field,$edit);
		}
	}
	function Save($insert){

		
		if(is_array($this->validates))
			$this->Validate($insert);

		if(!empty($this->errors)){
			if(!$insert)
				$_GET["edit"]=$_SESSION["id"];
				
			return false;	
		}
		if($insert){
			$ss = $this->Make_String_For_Insert_Query();	
			$q = "INSERT INTO $this->table".$ss;
		}
		else{
			$str = $this->Make_String_For_Update_Query();
			$q = "UPDATE $this->table
			  	  SET $str 
			      WHERE ".$this->primary_key." = ".$_SESSION["id"].";";
		}
		$res = mysql_query($q);		
	
		if(!$res){
			//die(mysql_error().$q);
			$this->errors [] = mysql_error().$q;
			if(!$insert)
				$_GET["edit"]=$this->object_Records[$this->primary_key];
			
			return false;
		}

		if($insert){
			$q = "SELECT ".$this->primary_key." FROM ".$this->table." ORDER BY ".$this->primary_key." DESC LIMIT 1;";
			//$q = "SELECT ".$this->primary_key.",MAX(updated_at) FROM ".$this->table.";";
			$res = mysql_query($q);
			$t = mysql_fetch_row($res);
			$_GET["id"] = $t[0];
			
		}
		else
			$_GET["id"] = $_SESSION["id"];
		
		return true;
	}

	protected function Make_String_For_Insert_Query(){
		$fieldsLength = sizeof($this->table_Fields_Names);
		$str ="(";
		for ($i=1; $i < $fieldsLength ; $i++) { 
			if($this->table_Fields_Names[$i]!= "updated_at")
				$str.= $this->table_Fields_Names[$i].",";
		}
		
		$str[strlen($str)-1] = ")";
		
	
		$str.="VALUES (";
		$len = sizeof($this->object_Records);	
		for ($j=1; $j < $len; $j++) { 
			if($this->form[$this->table_Fields_Names[$j]]=="text" || $this->form[$this->table_Fields_Names[$j]] == "textarea")
				$str.="'".mysql_real_escape_string($this->object_Records[$this->table_Fields_Names[$j]])."',";
			else
				$str.=$this->object_Records[$this->table_Fields_Names[$j]].",";

		}
		$str[strlen($str)-1] = ")";
		// die($str);
		return $str;
	}

	public function pp($key){
		$len = sizeof($this->Has_Many);
		$pos = strpos($key,"(");
	    $id = $key{$pos+1};
	    $q='';
		$arr = array();
	   	if($len > 1 ){
		 	$q = 'UPDATE '.$this->Has_Many[0]." INNER JOIN ";
		 	for ($i=1; $i < $len ; $i++) { 
		 		$q.=$this->Has_Many[$i].' USING('.$this->primary_key.')';
		 			if($i!=$len-1)
		 			$q.= " INNER JOIN ";
		 	}
		
		 	$q.=" SET ";
		  	foreach ($this->Has_Many as $table ) {
				$q.=$table.".".$this->primary_key." = ".$id.",";
		  	}
		  	$q[strlen($q)-1]=" ";	
		  	$q.=" WHERE ".$this->Has_Many[0].".".$this->primary_key." = ".$this->object_Records[$this->primary_key]." "; 
		}
		else{
			foreach ($this->Has_Many as $table) {
				# code...
			}
			$q.='UPDATE '.$table.
				' SET '.$this->primary_key.' = '.$id.
				' WHERE '.$this->primary_key.' = '.$this->object_Records[$this->primary_key];
		}
		return $q;
	}
	public function Delete($key){ //if is object then key referr to delete action else as primary key
		
		if($this->is_obj){
			if($key=="restrict"){
				if(is_array($this->Has_Many))
					$_SESSION["errors"] = "you need to delete related childs";
					return false;
			}
			if(preg_match('/move.*/', $key)){
				$len = sizeof($this->Has_Many);
				$pos = strpos($key,"(");
	    		$id = $key{$pos+1};
				if($len > 0){
				  foreach ($this->Has_Many as $table) {
				  	$q = "UPDATE ".$table.
				  		 " SET ".$this->primary_key." = ".$id.
				  		 " WHERE ".$this->primary_key." = ".$this->object_Records[$this->primary_key];
				  	if(!mysql_query($q)){
				  		$_SESSION["errors"] = mysql_error();
				  		return false;
				  	}
				  }
				}
				else{
					$_SESSION["errors"] = "Has_Many is empty,did u forget to declare has_many relation in $this->table model?"; 
					return false;
				}
			}
			$q = "DELETE FROM $this->table WHERE ".$this->primary_key." = ".$_POST["delete"];
		}
		else
			$q = "DELETE FROM $this->table WHERE $key = ".$_POST["delete"];
		
		if(!mysql_query($q)){
			$_SESSION["errors"] = mysql_error();
			return false;
		}
		else
			return true;
	}

	protected function Make_String_For_Update_Query(){
		$fieldsLength = sizeof($this->table_Fields_Names);
		
		for ($j=1; $j < $fieldsLength ; $j++)  
			if($this->table_Fields_Names[$j]!="updated_at")
				$str .= $this->table_Fields_Names[$j]." = '".$this->object_Records[$this->table_Fields_Names[$j]]."',"; 		
			
		$str[strlen($str)-1] = " ";
		 
			return $str;
		
	}

	function close(){
		//if($this->connect!=null)
			//mysql_close($this->connect);
		
		
		$this->status = "Connection Close";	
	}

	
	 function __destruct() {
	 	$this->close();
	 }


}



?>