<?php
	require_once '../control/conn.php';
	$isbn=$_POST['isbn'];
	$copy=$_POST['copy'];

	$flag=true;
    mysqli_query($link, 'start transaction');

	for($i=1;$i<=$copy;$i++){
		$count=1;
		while ($count!=0) {
			$random_number=generateCode();
			$query=mysqli_query($link, 'SELECT * FROM `book_copy` WHERE `code`="'.$random_number.'"');
			$count=mysqli_num_rows($query);
		}
		$num=checkCopy($i,$isbn);
		$query=mysqli_query($link, 'INSERT INTO `book_copy` (`isbn`,`code`,`number`,`status`)
			VALUES ("'.$isbn.'","'.$random_number.'","'.$num.'","on-shelf")');	
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
    	echo 'success'.$copy;
    }


	function checkCopy($num,$isbn){
		$query=mysqli_query($link, 'SELECT MAX(`number`) as max_copy FROM `book_copy` WHERE `isbn`="'.$isbn.'"');
		$fetch=mysqli_fetch_assoc($query);
		for($i=1;$i<=$fetch['max_copy'];$i++){
			$QUERY=mysqli_query($link, 'SELECT * FROM `book_copy` WHERE `isbn`="'.$isbn.'" AND `number`="'.$i.'"');
			$count=mysqli_num_rows($QUERY);
			if($count==0) return $i;
		}
		return $fetch['max_copy']+1;
	}
	function generateCode(){
		$code='';
		for($i=0;$i<=9;$i++){
			$code.=mt_rand(0,9);
		}
		return $code;
	}
?>