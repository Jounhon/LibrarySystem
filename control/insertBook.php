<?php
	require_once '../control/conn.php';
	$data=$_POST['data'];
	$authorsId='';
	$array=array();
	foreach ($data as $key => $value) {
		$str=explode("_", $value['selector']);
		if($str[0]=='authors'){
			$id=getAuthorId($value['value']);
			$authorsId=checkID($id,$authorsId);
		}
		else $array[]=$value['value'];
	}

	// 0-> title, 1-> isbn, 2-> classification, 3-> subclassification, 4->	publisher, 5-> date, 6-> copy
	$flag=true;
    mysqli_query($link, 'start transaction');
	
	$query=mysqli_query($link, 'INSERT INTO `book`(`isbn`,`title`,`main_class`,`sub_class`,`publisher`,`date`) 
		VALUES ("'.$array[1].'","'.$array[0].'","'.$array[2].'","'.$array[3].'","'.getPublisherId($array[4]).'","'.$array[5].'")');
	if(!$query){
		$flag=false;
		echo mysqli_error($conn);
	}

	$authorsIds=explode(",", $authorsId);
	for($i=0;$i<count($authorsIds);$i++){
		$query=mysqli_query($link, "INSERT INTO `book_author`(`author_id`,`isbn`) VALUES ('".$authorsIds[$i]."','".$array[1]."')");
		if(!$query){
			$flag=false;
			echo mysqli_error($conn);
		}
	}


	$storeCount=getBookCount($array[1]);
	$max=(int)$array[6]+(int)$storeCount;
	for($i=$storeCount+1;$i<=$max;$i++){
		$count=1;
		while ($count!=0) {
			$random_number=generateCode();
			$query=mysqli_query($link, 'SELECT * FROM `book_copy` WHERE `code`="'.$random_number.'"');
			$count=mysqli_num_rows($query);
		}
		$query=mysqli_query($link, 'INSERT INTO `book_copy` (`isbn`,`code`,`number`,`status`) 
			VALUES ("'.$array[1].'","'.$random_number.'","'.$i.'","on-shelf")');	
		if(!$query){
			$flag=false;
			echo mysqli_error($conn);
		}
	}
	if(!$flag){
		mysqli_query($link, 'ROLLBACK');
		echo 'error';
	}
    else {
    	mysqli_query($link, 'COMMIT');
    	echo 'success';
    }



	function getBookCount($isbn){
		$query=mysqli_query($link, 'SELECT * FROM `book_copy` WHERE `isbn`="'.$isbn.'"');
		$count=mysqli_num_rows($query);
		return $count;
	}
	function generateCode(){
		$code='';
		for($i=0;$i<=9;$i++){
			$code.=mt_rand(0,9);
		}
		return $code;
	}
	function getAuthorId($name){
		$query=mysqli_query($link, 'SELECT * FROM `author` WHERE `name`="'.$name.'"');
		$fetch=mysqli_fetch_assoc($query);
		return $fetch['id'];
	}
	function getPublisherId($name){
		$query=mysqli_query($link, 'SELECT * FROM `publisher` WHERE `name`="'.$name.'"');
		$fetch=mysqli_fetch_assoc($query);
		return $fetch['id'];
	}
	function checkID($id,$authorsId){
		if($authorsId=='') return $id;
		$i=explode(",", $authorsId);
		$repeat=false;
		foreach ($i as $key => $value) {
			if($value==$id){
				$repeat=true;
				break;
			}
		}
		if($repeat) return $authorsId;
		else return $authorsId.','.$id;

	}
?>