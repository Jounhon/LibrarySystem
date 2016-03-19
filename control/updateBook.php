<?php
	require_once '../control/conn.php';
	$data=$_POST['data'];
	$authorsId='';
	$array=array();
	foreach ($data as $key => $value) {
		$str=explode("_", $value['selector']);
		if($str[0]=='authors'){
			$id=getAuthorID($value['value']);
			$authorsId=checkID($id,$authorsId);
		}
		else $array[]=$value['value'];
	}
	// 0 -> title, 1 -> isbn, 2 -> classification, 3 -> subclassification, 4 ->	publisher, 5 -> date, 6 -> copy
	$flag=true;
    mysqli_query($link, 'start transaction');

    $query=mysqli_query($link, 'DELETE FROM `book_author` WHERE `isbn`="'.$array[1].'"');
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

	$query=mysqli_query($link, 'UPDATE `book` SET 
		`title`="'.$array[0].'",
		`main_class`="'.$array[2].'",
		`sub_class`="'.$array[3].'",
		`publisher`="'.getPublisherId($array[4]).'",
		`date`="'.$array[5].'" 
		WHERE `isbn`="'.$array[1].'"');
	if(!$query){
		$flag=false;
		echo mysqli_error($conn);
	}
	if(!$flag){
		mysqli_query($link, 'ROLLBACK');
		echo 'error';
	}
    else {
    	mysqli_query($link, 'COMMIT');
    	echo 'success';
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